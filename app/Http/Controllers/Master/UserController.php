<?php

namespace App\Http\Controllers\Master;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\BudgetAllotment;
use App\Models\Club;
use App\Models\Expenditures;
use App\Models\FinancialYear;
use App\Models\Member;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UtilityCertificate;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('masters.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.users.form')->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'login_id' => 'required',
            'password' => 'required',
            'office' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error',strip_tags($validator->errors()))->withInput();
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->login_id = $request->login_id;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $user->user_role_id = 2;
        $user->office_id = $request->office;
        $user->save();
        return back()->with('success',"User Created Successfully");
    }


    public function show(User $user)
    {
        return view('masters.users.show',compact('user'))->render();
    }


    public function edit(User $user)
    {
        return view('masters.users.form',compact('user'))->render();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'login_id' => 'required',
            'office' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->login_id = $request->login_id;
        $user->mobile = $request->mobile;
//        $user->user_role_id = 2;
        if($request->password){
           $user->password = Hash::make($request->password);
        }
        $user->office_id = $request->office;
        $user->save();
        return back()->with('success',"User Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success',"User Deleted Successfully");
    }

    public function profile()
    {
        $user = User::find(session()->get('user_id'));
        return view('login.profile',['user'=>$user]);
    }

    public function dashboard()
    {
        //  Urban Dashboard , location_type_id =  ( 1 for rural , 2 for urban)
        if(session()->get('location_type_id') == 2)
        {
            $Ward_count = Ward::where('city_id',session()->get('city_id'))->count();
            $club_count = Club::where('city_id',session()->get('city_id'))->count();
            $member_count = Member::get()->count();
            $user_id = session()->get('user_id');
            $user = User::find(session()->get('user_id'));
            $userRole = UserRole::find(session()->get('user_role_id'));
            $FY = FinancialYear::latest()->first();
            session()->put('FY_latest',$FY->name);
            $budget_allotted = BudgetAllotment::get();

            $member = Member::get();
            $gatividhi = Activity::count('activity_id');
            $gatividhi1 = Activity::where('event_type_id',1)->count();
            $gatividhi2 = Activity::where('event_type_id',2)->count();
            $gatividhi3 = Activity::where('event_type_id',3)->count();
            $gatividhi4 = Activity::where('event_type_id',4)->count();

            $cur_gatividhis = Activity::where('activity_date','<=',date('Y-m-d'))->
                                        where('activity_end_date','>=',date('Y-m-d'))->get();
            $cur_gatividhis_count = Activity::where('activity_date',date('Y-m-d'))->count();

            $expenditure_count = Expenditures::sum('amount');

            $uc_count = UtilityCertificate::count('utilization_certificate_id');
            $uc_current_month = UtilityCertificate::where('start_month',Carbon::now()->month)->count();


            return view('dashboard-user',['user'=>$user,'ward_count'=>$Ward_count,'club_count'=>$club_count,'member_count'=>$member_count,'userRole'=>$userRole,'budget_allotted'=>$budget_allotted,'gatividhi'=>$gatividhi,'gatividhi1'=>$gatividhi1,'gatividhi2'=>$gatividhi2,'gatividhi3'=>$gatividhi3,'gatividhi4'=>$gatividhi4,'member'=>$member,'cur_gatividhis'=>$cur_gatividhis,'cur_gatividhis_count'=>$cur_gatividhis_count,'expenditure_count'=>$expenditure_count,'uc_current_month'=>$uc_current_month,'uc_count'=>$uc_count]);
        }
        else
        {
            return response()->json(["message"=>"कार्य अंडर डेवलपमेंट में है"]);
        }
    }

}
