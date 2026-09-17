<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeSanction;
use App\Models\Block;
use App\Models\City;
use App\Models\Employee;
use App\Models\Grampanchayat;
use App\Models\Office;
use App\Models\Scheme;
use App\Models\TechnicalSanction;
use App\Models\Tender;
use App\Models\User;
use App\Models\Village;
use App\Models\Ward;
use App\Models\Work;
use App\Models\WorkStatus;
use App\Models\WorkCategory;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Reports extends Controller
{
    public function index()
    {
        
    }
    public function work_list(Request $request)
    {
        $workBuilder = Work::query();
        if(is_officer())
        {
            $workBuilder->where('office_id',session()->get('office_id'));
        }
        if(is_emp())
        {
            $workBuilder->where('employee_id',session()->get('emp_id'));
        }
        if($request->village || $request->grampanchayat || $request->block )
        {
            $workBuilder->leftJoin('villages', 'villages.village_id', '=', 'works.village_id');
            $workBuilder->leftJoin('grampanchayats', 'grampanchayats.grampanchayat_id', '=', 'villages.grampanchayat_id');
            $workBuilder->leftJoin('blocks', 'blocks.block_id', '=', 'grampanchayats.block_id');
        }
        if($request->city || $request->ward )
        {
            $workBuilder->leftJoin('wards', 'wards.ward_id', '=', 'works.ward_id');
            $workBuilder->leftJoin('cities', 'cities.city_id', '=', 'wards.city_id');
        }
        if($request->village)
            $workBuilder->where('villages.village_id',$request->village);
        if($request->grampanchayat)
            $workBuilder->where('grampanchayats.grampanchayat_id',$request->grampanchayat);
        if($request->block)
            $workBuilder->where('blocks.block_id',$request->block);
        if($request->ward)
            $workBuilder->where('wards.ward_id',$request->ward);
        if($request->city)
            $workBuilder->where('cities.city_id',$request->city);
        if($request->agency)
            $workBuilder->where('office_id',$request->agency);
        if($request->emp_agency)
        {      
            $workBuilder->where('employee_id','!=',0);
            $workBuilder->where('office_id',$request->emp_agency);
        }
        if($request->scheme)
            $workBuilder->where('scheme_id',$request->scheme);
        if($request->status)
            $workBuilder->where('work_status',$request->status);
        if($request->employee)
            $workBuilder->where('employee_id',$request->employee);
        if($request->agency_30days)
        {
            $workBuilder->where('office_id',$request->agency_30days);
            $workBuilder->where('created_at', '<=', now()->subDays(30)->toDateTimeString());
            // Match agency_wise_30days_pending(): completed/closed/rejected
            // works aren't "pending", so the click-through total must agree
            // with the count shown on the report.
            $workBuilder->whereNotIn('work_status', [10, 11, 12]);
        }
        if($request->status_30days)
        {
            $workBuilder->where('work_status',$request->status_30days);
            $workBuilder->where('created_at', '<=', now()->subDays(30)->toDateTimeString());
        }
            
        // village/grampanchayat/block/city/ward filters above left-join tables
        // that carry their own created_at column, so an unqualified latest()
        // ("order by created_at") is ambiguous and errors whenever a filter is
        // active. Qualify both the select and the order column to works.*.
        $work_data = $workBuilder->select('works.*')->orderBy('works.created_at', 'desc')->get();

        return view('reports.works.works',compact('request','work_data'));
    }
    public function work_details( $work)
    {
        $work = Work::find($work);
        $activity = LogActivity::with('User')
            ->forWork($work->work_id)
            ->orderBy('id', 'desc')
            ->get();
        return view('reports.works.work_detail',compact('work','activity'));
    }
    /**
     * The actual works behind a dashboard pending-count card: which works
     * still need a geo tag, or a UC/CC/RWH certificate.
     */
    public function pending_works(Request $request)
    {
        $type = $request->type;

        $titles = [
            'geo' => 'जियो टैग लंबित कार्य',
            'uc'  => 'यूसी अपलोड लंबित कार्य',
            'cc'  => 'सीसी अपलोड लंबित कार्य',
            'rwh' => 'आर.डब्लू.एच अपलोड लंबित कार्य',
        ];

        if (! array_key_exists($type, $titles)) {
            abort(404);
        }

        $builder = Work::query();
        if (is_officer()) {
            $builder->where('office_id', session()->get('office_id'));
        }
        if (is_emp()) {
            $builder->where('employee_id', session()->get('emp_id'));
        }

        if ($type === 'geo') {
            $builder->whereNull('latitude');
        } else {
            $builder->whereNotIn('work_status', [11, 12])
                ->whereDoesntHave('documents', fn ($q) => $q->where('doc_type', $type));
        }

        $work_data = $builder->latest()->get();
        $title = $titles[$type];

        return view('reports.pending_works', compact('work_data', 'title', 'type'));
    }

    /**
     * Every geo-tagged work the caller may see, plotted on one map.
     */
    public function work_map(Request $request)
    {
        $builder = Work::query()->with(['status']);

        if (is_officer()) {
            $builder->where('office_id', session()->get('office_id'));
        }
        if (is_emp()) {
            $builder->where('employee_id', session()->get('emp_id'));
        }
        if ($request->filled('financial_year')) {
            $builder->where('financial_year_id', $request->financial_year);
        }
        if ($request->filled('status')) {
            $builder->where('work_status', $request->status);
        }

        $untagged = (clone $builder)->whereNull('latitude')->count();

        $mapped = (clone $builder)
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->get()
            ->map(function ($work) {
                return [
                    'lat' => (float) $work->latitude,
                    'lng' => (float) $work->longitude,
                    'name' => $work->work_name,
                    'status' => $work->status->work_status_name ?? '',
                    'colour' => $this->statusColour($work->work_status),
                    'url' => route('reports.work-details', $work->work_id),
                ];
            })
            ->values();

        return view('reports.works.map', compact('mapped', 'untagged', 'request'));
    }

    /**
     * Marker colour by lifecycle position: complete, running, stopped, other.
     */
    private function statusColour($status)
    {
        if ($status == 10) {
            return '#2e7d32';
        }
        if (in_array($status, [8, 9])) {
            return '#f9a825';
        }
        if (in_array($status, [11, 12])) {
            return '#c62828';
        }
        return '#1565c0';
    }

    public function work_dossier($work)
    {
        $work = Work::findOrFail($work);
        return view('reports.works.dossier', compact('work'));
    }

    public function technical_sanction(Request $request)
    {
        $users = User::all();
        return view('reports.works',compact('request','users'));
    }
    public function administrative_sanction(Request $request)
    {
        $users = User::all();
        return view('reports.works',compact('request','users'));
    }
    public function block_wise(Request $request)
    {
        //Fetching Block Data
        $block_data = Block::all();
        foreach ($block_data as $block) {
            $workBuilder = Work::query();
            $workBuilder->leftJoin('villages', 'villages.village_id', '=', 'works.village_id');
            $workBuilder->leftJoin('grampanchayats', 'grampanchayats.grampanchayat_id', '=', 'villages.grampanchayat_id');
            $workBuilder->leftJoin('blocks', 'blocks.block_id', '=', 'grampanchayats.block_id');
            $workBuilder->where('blocks.block_id', $block->block_id);
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $block->total_works = $totalCountBuilder->count();
            $temp_data = array();
            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $block->work_stage_data = $temp_data;
        }
        return view('reports.block_wise.block_wise',compact('request','block_data'));
    }
    public function grampanchayat_wise(Request $request)
    {
        //Fetching Grampanchayat Data
        $gp_data = Grampanchayat::where('block_id',$request->block)->get();
        foreach ($gp_data as $gp) {
            $workBuilder = Work::query();
            $workBuilder->leftJoin('villages', 'villages.village_id', '=', 'works.village_id');
            $workBuilder->leftJoin('grampanchayats', 'grampanchayats.grampanchayat_id', '=', 'villages.grampanchayat_id');
//            $workBuilder->leftJoin('blocks', 'blocks.block_id', '=', 'grampanchayats.block_id');
            $workBuilder->where('grampanchayats.grampanchayat_id', $gp->grampanchayat_id);
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $gp->total_works = $totalCountBuilder->count();
            $temp_data = array();
            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $gp->work_stage_data = $temp_data;
        }
        return view('reports.block_wise.grampanchayat_wise',compact('request','gp_data'));
    }
    public function village_wise(Request $request)
    {
        //Fetching Village Data
        $village_data = Village::where('grampanchayat_id',$request->grampanchayat)->get();
        foreach ($village_data as $gp) {
            $workBuilder = Work::query();
            $workBuilder->leftJoin('villages', 'villages.village_id', '=', 'works.village_id');
//            $workBuilder->leftJoin('grampanchayats', 'grampanchayats.grampanchayat_id', '=', 'villages.grampanchayat_id');
//            $workBuilder->leftJoin('blocks', 'blocks.block_id', '=', 'grampanchayats.block_id');
            $workBuilder->where('villages.village_id', $gp->village_id);
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $gp->total_works = $totalCountBuilder->count();
            $temp_data = array();
            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $gp->work_stage_data = $temp_data;
        }

        return view('reports.block_wise.village_wise',compact('request','village_data'));
    }
    public function city_wise(Request $request)
    {
        //Fetching City Data
        $city_data = City::all();
        foreach ($city_data as $city) {
            $workBuilder = Work::query();
            $workBuilder->leftJoin('wards', 'wards.ward_id', '=', 'works.ward_id');
            $workBuilder->leftJoin('cities', 'cities.city_id', '=', 'wards.city_id');
            $workBuilder->where('cities.city_id', $city->city_id);
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $city->total_works = $totalCountBuilder->count(); 
            $temp_data = array();

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $city->work_stage_data = $temp_data;
        }
        return view('reports.city_wise.city_wise',compact('request','city_data'));
    }
    public function ward_wise(Request $request)
    {
        //Fetching City Data
        $ward_data = Ward::where('city_id',$request->city)->get();
        foreach ($ward_data as $ward) {
            $workBuilder = Work::query();
            $workBuilder->leftJoin('wards', 'wards.ward_id', '=', 'works.ward_id');
//            $workBuilder->leftJoin('cities', 'cities.city_id', '=', 'wards.city_id');
            $workBuilder->where('wards.ward_id', $ward->ward_id);
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $ward->total_works = $totalCountBuilder->count();
            $temp_data = array();

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $ward->work_stage_data = $temp_data;
        }
        return view('reports.city_wise.ward_wise',compact('request','ward_data'));
    }
    public function agency_wise(Request $request)
    {
        //Fetching Agency Data
        // Every office is a real agency; there is no reserved id to exclude.
        $office_data = Office::all();
        if (is_officer() || is_emp()) {
            $office_data = $office_data->where('office_id', session()->get('office_id'))->values();
        }
        foreach ($office_data as $office) {
            $workBuilder = Work::query();
            $workBuilder->where('office_id', $office->office_id);
            $totalCountBuilder = $workBuilder;
            $office->total_works = $totalCountBuilder->count();
            $temp_data = array();

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $office->work_stage_data = $temp_data;
        }
        return view('reports.agency_wise.agency_wise',compact('request','office_data'));
    }
    /**
     * Works grouped by the category their work type belongs to.
     */
    public function category_wise(Request $request)
    {
        $category_data = WorkCategory::withCount('work_types')->orderBy('work_category_name')->get();

        foreach ($category_data as $category) {
            $typeIds = $category->work_types->pluck('work_type_id');

            $workBuilder = Work::query()->whereIn('work_type_id', $typeIds);
            if (is_officer()) {
                $workBuilder->where('office_id', session()->get('office_id'));
            }
            if (is_emp()) {
                $workBuilder->where('employee_id', session()->get('emp_id'));
            }

            $category->total_works = (clone $workBuilder)->count();
            $category->sanctioned = (float) (clone $workBuilder)->sum('sanction_amount');

            $temp_data = [];
            foreach (get_work_statuses() as $status) {
                $temp_data[$status->work_status_id] = (clone $workBuilder)
                    ->where('work_status', $status->work_status_id)->count();
            }
            $category->work_stage_data = $temp_data;
        }

        return view('reports.category_wise.category_wise', compact('request', 'category_data'));
    }

    public function scheme_wise(Request $request)
    {
        //Fetching Agency Data
        $scheme_data = Scheme::all();
        foreach ($scheme_data as $scheme) {
            $workBuilder = Work::query();
            if(is_officer())
            {
                $workBuilder->where('office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $workBuilder->where('scheme_id', $scheme->scheme_id);
            $totalCountBuilder = $workBuilder;
            $scheme->total_works = $totalCountBuilder->count();
            $temp_data = array();

            foreach (get_work_statuses() as $status)
            {

                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $scheme->work_stage_data = $temp_data;
        }
        return view('reports.scheme_wise.scheme_wise',compact('request','scheme_data'));
    }
    public function scheme_wise_work(Request $request)
    {
        $scheme = Scheme::find($request->scheme);
        $status = WorkStatus::find($request->status);
        return view('reports.scheme_wise.scheme_wise_work_list',compact('request','scheme','status'));
    }
    public function ts_view($technicalSanction)
    {
        $technicalSanction = TechnicalSanction::find($technicalSanction);
        return view('reports.works.ts_view',compact('technicalSanction'))->render();
    }
    public function as_view($administrativeSanction)
    {
        $administrativeSanction = AdministrativeSanction::find($administrativeSanction);
        return view('reports.works.as_view',compact('administrativeSanction'))->render();
    }
    public function tender_view($tender)
    {
        $tender = Tender::find($tender);
        return view('reports.works.tender_view',compact('tender'))->render();
    }
    public function progress_view($work)
    {
        $work = Work::find($work);
        return view('reports.works.progress_view',compact('work'))->render();
    }
    public function logs_list()
    {       
        $logs_list = LogActivity::orderBy('id','ASC')->get();
        return view('reports.logs.logs_list',compact('logs_list'));
    }
    public function logs_filter(Request $request)
    {
        $logsBuilder = LogActivity::query();
    
        if($request->agency){
            $logsBuilder->leftJoin('users', 'users.user_id', '=', 'log_activities.user_id');
            $logsBuilder->leftJoin('offices', 'offices.office_id', '=', 'users.user_id');
            $logsBuilder->where('offices.office_id',$request->agency);
            $logsBuilder->select('subject','office_name','log_activities.created_at as logsDate');
        }
        elseif($request->from_date && $request->to_date)
        {
            $logsBuilder->leftJoin('users', 'users.user_id', '=', 'log_activities.user_id');
            $logsBuilder->leftJoin('offices', 'offices.office_id', '=', 'users.user_id');
            $logsBuilder->whereBetween('log_activities.created_at',[$request->from_date, $request->to_date]);
            $logsBuilder->select('subject','office_name','log_activities.created_at as logsDate');

        }
        
        elseif($request->from_date)
        {           
            $logsBuilder->leftJoin('users', 'users.user_id', '=', 'log_activities.user_id');
            $logsBuilder->leftJoin('offices', 'offices.office_id', '=', 'users.user_id');
            $logsBuilder->whereDate('log_activities.created_at','>=',$request->from_date);
            $logsBuilder->select('subject','office_name','log_activities.created_at as logsDate');

        }
        elseif($request->to_date)
        {
            $logsBuilder->leftJoin('users', 'users.user_id', '=', 'log_activities.user_id');
            $logsBuilder->leftJoin('offices', 'offices.office_id', '=', 'users.user_id');
            $logsBuilder->where('log_activities.created_at','<=',$request->to_date);
            $logsBuilder->select('subject','office_name','log_activities.created_at as logsDate');
        }
        // elseif($request->agency && ($request->from_date && $request->to_date))
        else
        {
            $logsBuilder->leftJoin('users', 'users.user_id', '=', 'log_activities.user_id');
            $logsBuilder->leftJoin('offices', 'offices.office_id', '=', 'users.user_id');
            $logsBuilder->whereBetween('log_activities.created_at',[$request->from_date, $request->to_date]);
            $logsBuilder->where('offices.office_id',$request->agency);
            $logsBuilder->select('subject','office_name','log_activities.created_at as logsDate');
        }
        $logs_filter = $logsBuilder->orderBy('id','ASC')->get();
        return view('reports.logs.logs_list',compact('logs_filter'));
    }    

    public function last_activity(Request $request)
    {
        //Fetching Agency Data
        // Every office is a real agency; there is no reserved id to exclude.
        $office_data = Office::all();
        if (is_officer() || is_emp()) {
            $office_data = $office_data->where('office_id', session()->get('office_id'))->values();
        }
        foreach ($office_data as $office) {
            $workBuilder = Work::query();
            $workBuilder->where('office_id', $office->office_id);
            $totalCountBuilder = $workBuilder;
            $office->total_works = $totalCountBuilder->count();
            $temp_data = array();

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $office->work_stage_data = $temp_data;
        }
        return view('reports.logs.CheckUploade',compact('request','office_data'));

        // $file = $request->file('file_upload');
        // dd($file);
        // if ($file->getClientOriginalExtension() === 'pdf' || $file->getClientOriginalExtension() === 'docx') {
        //     // It's a document (PDF or DOCX)
        // } elseif (in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif'])) {
        //     // It's an image (JPG, JPEG, PNG, GIF)
        // } else {
        //     // It's neither a document nor an image
        // }

    }

    public function employee_agency_wise(Request $request)
    {
        //Fetching Agency Data
        // Every office is a real agency; there is no reserved id to exclude.
        $office_data = Office::all();
        if (is_officer() || is_emp()) {
            $office_data = $office_data->where('office_id', session()->get('office_id'))->values();
        }
        foreach ($office_data as $office) {
            $workBuilder = Work::query();
            $workBuilder->leftjoin('employees','employees.emp_id','works.employee_id');
            $workBuilder->where('works.employee_id','!=',0);
            $workBuilder->where('works.office_id', $office->office_id);
            $totalCountBuilder = $workBuilder;
            $office->total_works = $totalCountBuilder->count();
            $temp_data = array(); 

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $office->work_stage_data = $temp_data;
        }
        return view('reports.Employee_wise.agency_wise',compact('request','office_data'));
    }

    public function employee_wise(Request $request)
    {
        $emp_data = Employee::where('office_id',$request->agency)->get();
        foreach($emp_data as $emp)
        {
            $workBuilder = Work::query();
            $workBuilder->leftjoin('employees','employees.emp_id','works.employee_id');
            // $workBuilder->leftjoin('employees_designation','employees_designation.designation_id','employees.emp_designation_id');
            $workBuilder->where('works.employee_id',$emp->emp_id);
            if(is_officer())
            {
                $workBuilder->where('works.office_id',session()->get('office_id'));
            }
            if(is_emp())
            {
                $workBuilder->where('employee_id',session()->get('emp_id'));
            }
            $totalCountBuilder = $workBuilder;
            $emp->total_works = $totalCountBuilder->count();
            $temp_data = array();
            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $emp->work_stage_data = $temp_data;
            
        }
        return view('reports.Employee_wise.employees_wise',compact('request','emp_data'));

    }

    public function last_status(Request $request)
    {
        $work_data = Work::latest()->get();
        return view('reports.last_status.last_status',compact('request','work_data'));
    }

    public function uploaded_docs(Request $request)
    {
        // Fetching Agency Data
        // Every office is a real agency; there is no reserved id to exclude.
        $office_data = Office::all();
        if (is_officer() || is_emp()) {
            $office_data = $office_data->where('office_id', session()->get('office_id'))->values();
        }
        foreach ($office_data as $office) {
            $totalCountBuilder = Work::where('office_id', $office->office_id);
    
            $office->total_works = $totalCountBuilder->count();
            $temp_data = array();
    
            $office->work_id = $totalCountBuilder->pluck('work_id')->toArray();
    
            // Counting documents for each table
            $tables = [
                'administrative_sanctions',
                'technical_sanctions',
                'tenders',
                'work_progress',
                'work_completes',
            ];
    
            foreach ($tables as $table) {
                $tempBuilder = clone $totalCountBuilder;
                $temp_data[$table] = $tempBuilder->join($table, 'works.work_id', '=', $table . '.work_id')
                    ->where('works.office_id', $office->office_id)
                    ->count($table . '.upload_file');
            }
            
            // dd($office->office_id);
            // dd($temp_data);

    
            $office->work_stage_data = $temp_data;
        }
        return view('reports.ac_uploaded_docs.agency_wise', compact('request', 'office_data'));
    }

    public function agency_wise_30days_pending(Request $request)
    {

        //Fetching Agency Data
        // Every office is a real agency; there is no reserved id to exclude.
        $office_data = Office::all();
        if (is_officer() || is_emp()) {
            $office_data = $office_data->where('office_id', session()->get('office_id'))->values();
        }
        foreach ($office_data as $office) {
            $workBuilder = Work::query();
            $workBuilder->where('office_id', $office->office_id);
            $workBuilder->where('created_at', '<=', now()->subDays(30)->toDateTimeString());
            // Completed/closed/rejected works are done; an old one finished
            // long ago isn't "pending" just because it's old.
            $workBuilder->whereNotIn('work_status', [10, 11, 12]);
            $totalCountBuilder = $workBuilder;
            $office->total_works = $totalCountBuilder->count();
            $temp_data = array();

            foreach (get_work_statuses() as $status)
            {
                $tempBuilder = clone $workBuilder;
                $temp_data[$status->work_status_id] = $tempBuilder->where('work_status',$status->work_status_id)->count();
            }
            $office->work_stage_data = $temp_data;
        }
        return view('reports.agency_wise.agency_wise_30days_pending',compact('request','office_data'));
    }


}
