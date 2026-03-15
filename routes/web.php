<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Models\Trainee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\FundController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\TraineeController;
use App\Http\Controllers\EmployerController;
use App\Http\Middleware\CheckEmployerStatus;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployerAuthController;
use App\Http\Controllers\ActivityLogController;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Employer Login
Route::get('/employer/login', function () {
  return view('auth.employer_auth.login_employeer');
})->name('empLogin');

Route::post('/employer/login', [EmployerAuthController::class, 'login'])->name('employer.login');

// Protecting Routes with Middleware
Route::prefix('employer')->middleware(['auth:employer', 'check.employer.status'])->group(function () {
  Route::get('/showEmployee', [EmployerAuthController::class, 'showEmployerDashboard'])->name('employer.dashboard');
  Route::post('/showEmployee', [EmployerAuthController::class, 'submitFilters'])->name('employer.filters');

  // Trainee Info Route
  Route::get('/trainee_information/{id}', [EmployerController::class, 'show'])->name('trainee.information');

  Route::post('/add_to_short_listed', [EmployerController::class, 'addToShortlisted'])->name('employer.add_to_short_listed');

  Route::get('/showTraineeList', [EmployerController::class, 'showShortList'])->name('employer.shortList');

  Route::get('/showTraineeListUpdate/{id}', [EmployerController::class, 'showTraineeListUpdate'])->name('employer.showTraineeListUpdate');
});

// Employer Logout
Route::post('/employer/logout', [EmployerAuthController::class, 'emplogout'])->name('employer.logout');


//  Route::post('/employer/logout', function () {
//   // dd('test');
//   return view('auth.login');
// })->name('empLogin');

Route::get('/', function () {
  return view('auth.login');
})->name('login');

Route::get('/academy/{academy}',[DashboardController::class,'academyDashboard'])->name('academy.dashboard')->middleware('auth');

Auth::routes();
Route::get('/home', [DashboardController::class,'jobcoachDashboard'])->name('jobcoachDashboard')->middleware('auth');
Route::get('/statistics', [DashboardController::class,'statisticsDashboard'])->name('statistics.dashboard')->middleware('auth');
//Route::get('/home', [DashboardController::class,'jobcoachDashboard'])->name('jobcoachDashboard')->middleware('auth');

Route::prefix('ets')->middleware('auth')->group(function () {

    // Dashboard
//    Route::get('/', [DashboardController::class, 'index'])->name('jobcoachDashboard');

    // view all trainees based academy_id & cohort_id
    Route::get('/trainees/academy/{academy}/cohort/{cohort}', [TraineeController::class, 'index'])->name('trainees.index');
// // view all trainess how have internshipe based academy_id & cohort_id
    Route::get('/traineLog/academy/{academy}/cohort/{cohort}', [TraineeController::class, 'traineeLog'])->name('traineeLog.index');
    Route::get('/employment-status/{id}/edit', [TraineeController::class, 'edit'])->name('employment-status.edit');
    Route::patch('/employment-status/{id}/update', [TraineeController::class, 'update'])->name('employment-status.update');
    Route::get('/employment-status/{id}/destroy', [TraineeController::class, 'destroy'])->name('employment-status.destroy');


    Route::get('/employment-status/{id}/logs', [TraineeController::class, 'logs'])->name('employment-status.logs');
    Route::get('/employment-status/{id}/logs/create', [TraineeController::class, 'createLog'])->name('employment-status.logs.create');
    Route::delete('/employment-status/{traineeId}/logs/{logId}/destroy', [TraineeController::class, 'destroyLog'])->name('employment-status.logs.destroy');

    Route::post('/employment-status/{id}/logs', [TraineeController::class, 'storeLog'])->name('employment-status.logs.store');
    Route::get('/edit/trainee/{id}/log/{logId}',[TraineeController::class,'show_update_page'])->name('employment-status.logs.editForm');

// update data
    Route::put('/trainees/log/{log}', [TraineeController::class, 'updateLog'])->name('trainee.updateLog');

    Route::get('/ManegerPages',[CohortController::class,'mangerAccess'])->name('testManager');

    // trainee_log
    Route::get('/export-trainee-logs/{academy_id}/{cohort_id}', [TraineeController::class, 'traineeLogExportData'])->name('export.trainee.logs');

    Route::get('/trainees', [TraineeController::class, 'showAll'])->name('trainees.showAll');
//Search route
//Route::get('/trainees/search', [TraineeController::class, 'search_result'])->name('trainees.search_result');

// trainee listz
    Route::get('/import/trainees', [TraineeController::class, 'importTrainees'])->name('import.trainees');
    Route::post('/import/trainees', [TraineeController::class, 'importTrainees'])->name('trainees.import');
    Route::get('/export/allExport', [TraineeController::class, 'export'])->name('export.all');

    Route::get('/export-trainees-all', [TraineeController::class, 'exportTraineesAll'])->name('export.trainees.all');

    Route::get('/export-trainees/{academy_id}', [TraineeController::class, 'exportTrainees'])->name('export.trainees');
// trainee status
    Route::get('/employment-status/{traineeId}/logs/export', [TraineeController::class, 'exportLogs'])->name('employment-status.logs.export');
// Route::get('/employment-status/create-log', [EmploymentStatusController::class, 'createLog'])->name('employment-status.create-log');
    Route::get('/trainees/{id}/profile', [TraineeController::class, 'showProfile'])->name('trainees.profile');

//survey trainee
    Route::get('/trainees/studentSurvey/{id}', [SurveyController::class, 'studentSurvey'])->name('studentSurvey');



//survey

    Route::get('/Survey/survey1', [SurveyController::class, 'survey1'])->name('survey1');
    Route::get('/Survey/survey2', [SurveyController::class, 'survey2'])->name('survey2');
    Route::get('survey/detail/{id}',[SurveyController::class,'showSurveyDetail'])->name('survey.detail');

    Route::post('/save-survey-data', [SurveyController::class, 'saveSurveyData'])->name('save.survey.data');
    Route::get('/survey/thankyou', [SurveyController::class, 'thankYou'])->name('survey.thankyou');
    Route::post('/Survey/survey1/send', [SurveyController::class, 'sendSurvey'])->name('sendSurvey');
    Route::post('/Survey/survey1/send-notification', [SurveyController::class, 'sendNotification'])->name('survey.sendNotification');
    Route::get('/api/trainees', [SurveyController::class, 'fetchTrainees']);
//log
    Route::get('/Survey/survey-logs', [\App\Http\Controllers\SurveyLogController::class, 'index'])->name('survey.logs');
    Route::get('/Survey/Result/{academyId}', [SurveyController::class, 'surveyResult'])->name('surveyResult');
    Route::get('/academy/Result/tableSurveyDetails/{academy}/{academy_slug}/{cohort_id}', [SurveyController::class, 'tableSurveyDetails'])->name('tableSurveyDetails');

    //Company
    Route::get('/names_companies', [CompanyController::class,'show'])->name('names_companies.showCompaniesForm');

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/filter', [CompanyController::class, 'filter'])->name('companies.filter');
    Route::get('/employees.search', [EmployerController::class, 'fillter'])->name('employees.fillter');
    Route::get('/search-companies', [CompanyController::class, 'searchCompanies'])->name('search.companies');
// crud employee
    Route::post('/employees/{id}', [EmployerController::class, 'destroy'])->name('employees.destroy');

    Route::get('/employees/{id}/edit', [EmployerController::class, 'edit'])->name('com_employer.edit');

    Route::post('/employer/update/{id}', [EmployerController::class, 'update'])->name('employer.storeEditEmployer');



    Route::get('/companies/{id}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/addCompany', [CompanyController::class, 'addCompany'])->name('addCompany');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{id}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{id}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{companyId}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    Route::get('/export/companies', [CompanyController::class, 'export'])->name('export.companies');
    Route::post('/import/companies', [CompanyController::class, 'import'])->name('import.companies');
    Route::patch('/companies/{id}/update-type-of-deal', [CompanyController::class, 'updateTypeOfDeal'])->name('companies.updateTypeOfDeal');


    Route::get('/employees', [CompanyController::class, 'showAllEmployees'])->name('companies.showAll');



    Route::get('/employer/add', [CompanyController::class, 'addEmployer'])->name('employer.addEmployer');
    Route::post('/employer/store', [CompanyController::class, 'storeEmployer'])->name('employer.storeEmployer');
    Route::get('/employees/export', [EmployerController::class, 'export'])->name('employees.export');
    Route::post('/employees/import', [EmployerController::class, 'import'])->name('employees.import');

    Route::get('/trainees/filter', [TraineeController::class, 'filter'])->name('trainees.filter');



// Funds CRUD
    Route::get('/funds', [FundController::class, 'index'])->name('fund.manageFund');
    Route::get('/funds/create/fund', [FundController::class, 'create_fund'])->name('fund.createFund');
    Route::post('/funds/create/fund/store', [FundController::class, 'store_new_fund'])->name('fund.store_new_Fund');
    Route::get('/funds/update/data/{id}', [FundController::class, 'update_fund_data'])->name('fund.fund_update_info');
    Route::patch('/funds/update/store/{id}', [FundController::class, 'fund_store_data'])->name('fund.update');
// cohort fund
    Route::get('/funds/cohort/{id}', [FundController::class, 'show_cohort_fund'])->name('fund.show_cohort_related_fund');

    Route::get('/funds/cohort/update/{id}', [FundController::class, 'update_cohort_fund'])->name('fund.edit_cohort_fund');

    Route::patch('/funds/cohort/store/update/{id}', [FundController::class, 'store_update_cohort_fund'])->name('cohortFund.store_update');

    Route::get('/funds/create/cohort/{id}', [FundController::class, 'create_cohort_fund'])->name('fund.create_cohort_fund');

    Route::patch('/funds/store/new/fund', [FundController::class, 'store_new_cohort_fund'])->name('store_new_cohort_func');


    Route::delete('/funds/cohort/delete/{id}', [FundController::class, 'delete_cohort_fund'])->name('fund.destroy_fund');

//user Crud   user_details.manageUser
    Route::get('/mangeUser', [UserController::class, 'viewUser'])->name('user_details.manageUser');
    Route::get('/mangeUser/update/{id}', [UserController::class, 'update_user_data'])->name('user_details.user_update_info');
    Route::patch('/mangeUser/update/store/{id}', [UserController::class, 'user_store_data'])->name('user.store_update');
    Route::delete('/mangeUser/delete_user/{id}', [UserController::class, 'destroy'])->name('manage_user.delete_user');
    Route::get('/mangeUser/create_user', [UserController::class, 'create'])->name('user_details.create_new_user');

    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    Route::get('/notification', [ActivityLogController::class, 'index'])->name('user_notification');
// web.php
    Route::post('/activity-log/{log}/mark-read', [ActivityLogController::class, 'markAsRead'])
        ->name('activity.markRead');
    Route::post('/activity-log/mark-all-read', [ActivityLogController::class, 'markAllRead']);



});
// Route::prefix('Employer')->middleware(['auth','CheckUserRole:Employer'])->group(function(){
//   Route::get('/testEmployer',[CohortController::class,'employerAccess'])->name('testEmployer');
// });
