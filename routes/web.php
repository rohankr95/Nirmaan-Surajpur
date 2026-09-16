 <?php

use App\Http\Controllers\AdministrativeSanctionController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Master;
use App\Http\Controllers\Master\CityController;
use App\Http\Controllers\Reports;
use App\Http\Controllers\TechnicalSanctionController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\WorkClosedController;
use App\Http\Controllers\WorkCompleteController;
use App\Http\Middleware\AlreadyLoggedIn;
use App\Http\Middleware\IsLoggedIn;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\WorkProgressController;
use App\Http\Controllers\WorkRejectController;
use App\Models\TechnicalSanction;
use App\Models\WorkComplete;
use App\Models\WorkReject;

Route::get('/', function () {
    return redirect()->route('login.index');
})->name('index');
Route::get('login', [LoginController::class, 'index'])->name('login.index')->middleware(AlreadyLoggedIn::class);
Route::post('authenticate', [LoginController::class, 'auth'])->name('authenticate');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware([IsLoggedIn::class])->group(function () {

    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::get('profile',[LoginController::class,'profile'])->name('profile');
    Route::get('change-password',[LoginController::class,'change_pwd'])->name('change-password');
    Route::post('update-pofile/{id}',[LoginController::class,'update_profile'])->name('update-profile');
    Route::post('update-password/{id}',[LoginController::class,'change_password'])->name('update-password');
    //Masters Routes
    Route::prefix('master')->group(function () {
        //Location Master
        Route::resource("parliamentary_constituency",Master\ParliamentaryConstituencyController::class);
        Route::resource("assembly_constituency",Master\AssemblyConstituencyController::class);
        Route::resource("states",Master\StateController::class);
        Route::resource("subdivisions",Master\SubdivisionController::class);
        Route::resource("district",Master\DistrictController::class);
        Route::resource("blocks",Master\BlockController::class);
        // Historic naming: the "villages" resource lists gram panchayats.
        Route::resource("villages",Master\GrampanchayatController::class);
        Route::resource("village",Master\VillageController::class);
        Route::resource("contractor",Master\ContractorController::class);
        Route::resource("cities",Master\CityController::class);
        Route::resource("wards",Master\WardController::class);

        Route::resource("schemes",Master\SchemeController::class);
        Route::resource("work_types",Master\WorkTypeController::class);
        Route::resource("departments",Master\DepartmentController::class);
        Route::resource("offices",Master\OfficeController::class);
        Route::resource("users",Master\UserController::class);
        // Route::resource("engineers",Master\EngineerController::class);
        Route::resource("employee",Master\EmployeeController::class);
        Route::resource("employee-designation",Master\EmployeeDesignationController::class);

    });
    // Work payment ledger
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/history', [PaymentController::class, 'history'])->name('payments.history');
    Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Used for work crud
    Route::resource('work', WorkController::class);
    Route::post('api/fetch-CityToWard', [WorkController::class, 'fetchCityToWard']);
    Route::post('api/fetch-BlockToGrampanchayat', [WorkController::class, 'fetchBlockToGrampanchayat']);
    // Route::post('api/fetch-DepartmentToOffice', [WorkController::class, 'fetchDepartmentToOffice']);
    Route::post('api/fetch-GpToVillage', [WorkController::class, 'fetchGpToVillage']);
    Route::post('api/fetch-OfficeToEmployee', [WorkController::class, 'fetchOfficeToEmployee']);

    // Used for Technical Sanction crud
    Route::resource('technical-sanction', TechnicalSanctionController::class);

    // Used for Administrative Saction crud
    Route::resource('administrative-sanction', AdministrativeSanctionController::class);

    //Used for Tender crud
    Route::resource('tender',TenderController::class);

    // Used for Work Progress crud
    Route::resource('work-progress', WorkProgressController::class);
    Route::post('api/fetch-WorkToMbStages', [WorkProgressController::class, 'fetchWorkToMBStages']);
    Route::post('api/fetch-WorkToWorkStatus', [WorkProgressController::class, 'fetchWorkToWorkStatus']);
    Route::post('api/fetch-StatusToTS', [WorkProgressController::class, 'fetchStatusToTS']);
    Route::post('api/fetch-StatusToAS', [WorkProgressController::class, 'fetchStatusToAS']);
    Route::post('work-progress-ts', [DashboardController::class, 'work_progress_ts'])->name('work-progress-ts');
    Route::post('work-progress-As', [DashboardController::class, 'work_progress_As'])->name('work-progress-As');
    Route::post('work-progress-Tender', [DashboardController::class, 'work_progress_Tender'])->name('work-progress-Tender');

    // work-completion & rejection routes
    Route::post('work-closed',[WorkClosedController::class,'store'])->name('work-closed');
    Route::post('work-complete',[WorkCompleteController::class,'store'])->name('work-complete');
    Route::post('work-agreement',[AgreementController::class,'store'])->name('work-agreement');
    Route::post('work-reject',[WorkRejectController::class,'store'])->name('work-reject');

    //Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get("works",[Reports::class,'work_list'])->name('reports.works');
        Route::get("work-details/{id}",[Reports::class,'work_details'])->name('reports.work-details');
        Route::get("work-dossier/{id}",[Reports::class,'work_dossier'])->name('reports.work-dossier');
        Route::get("work-map",[Reports::class,'work_map'])->name('reports.work-map');
        Route::get("ts-view/{id}",[Reports::class,'ts_view'])->name('reports.ts-view');
        Route::get("as-view/{id}",[Reports::class,'as_view'])->name('reports.as-view');
        Route::get("progress-view/{id}",[Reports::class,'progress_view'])->name('reports.progress-view');
        Route::get("tender-view/{id}",[Reports::class,'tender_view'])->name('reports.tender-view');
        Route::get("technical-sanction",[Reports::class,'technical_sanction'])->name('reports.technical-sanction');
        Route::get("administrative-sanction",[Reports::class,'administrative_sanction'])->name('reports.administrative-sanction');
        Route::get("block-wise",[Reports::class,'block_wise'])->name('reports.block-wise');
        Route::get("grampanchayat-wise",[Reports::class,'grampanchayat_wise'])->name('reports.grampanchayat-wise');
        Route::get("villege-wise",[Reports::class,'village_wise'])->name('reports.villege-wise');
        Route::get("city-wise",[Reports::class,'city_wise'])->name('reports.city-wise');
        Route::get("ward-wise",[Reports::class,'ward_wise'])->name('reports.ward-wise');
        Route::get("agency-wise",[Reports::class,'agency_wise'])->name('reports.agency-wise');
        Route::get("scheme-wise",[Reports::class,'scheme_wise'])->name('reports.scheme-wise');
        Route::get("scheme-wise-work-list",[Reports::class,'scheme_wise_work'])->name('reports.scheme-wise-work-list');
        Route::get("logs-list",[Reports::class,'logs_list'])->name('reports.logs-list');
        Route::post("logs-index",[Reports::class,'logs_filter'])->name('reports.logs_filter');
        Route::get("counts-uploaded-docs",[Reports::class,'uploaded_docs'])->name('reports.counts-uploaded-docs');
        Route::get("employee-agency-wise",[Reports::class,'employee_agency_wise'])->name('reports.employee-agency-wise');
        Route::get("employee-wise",[Reports::class,'employee_wise'])->name('reports.employee-wise');
        Route::get("last-status",[Reports::class,'last_status'])->name('reports.last-status');
        Route::get("agency-wise-30-days-pending",[Reports::class,'agency_wise_30days_pending'])->name('reports.agency-wise-30-days-pending');

    });

    Route::post('api/fetch-WorkTypeToMBStages', [WorkController::class, 'fetchWorkTypeToMBStages']);


});
