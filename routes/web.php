<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AppsController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[AppsController::class,'index']);





//Route::get('logout_admin','AdminController@logout')->name('logout_admin');




//Report Start
Route::get('report/{type}',[AdminController::class,'report_view']);

Route::get('show_order_report',[ReportController::class,'show_order_report'])->name('show_order_report');

//




//apps start
Route::get('show-all-apps',[AppsController::class,'show_all_apps'])->name('show-all-apps');
Route::get('add-apps',[AppsController::class,'add_apps_ui']);
Route::post('add-apps',[AppsController::class,'add_apps'])->name('add-apps');
Route::get('edit_apps_content/{id}',[AppsController::class,'edit_apps_content_ui'])->name('edit_apps_content');
Route::post('update_apps_content',[AppsController::class,'update_apps_content'])->name('update_apps_content');
Route::get('apps_content_delete/{id}',[AppsController::class,'apps_content_delete'])->name('apps_content_delete');

//apps end

//Report Start
//Route::view('report','admin.report.date_view');
Route::get('report',[AppsController::class,'report'])->name('reports');
Route::post('show_subscription_report',[AppsController::class,'show_subscription_report'])->name('show_subscription_report');

//Report End

//content start
Route::get('content',[AppsController::class,'content'])->name('report');
Route::get('show-all-content',[AppsController::class,'show_all_content'])->name('show-all-content');
Route::get('add-content',[AppsController::class,'add_content_ui'])->name('add-content');
Route::post('update_data',[AppsController::class,'update_data'])->name('update_data');
Route::post('save_content',[AppsController::class,'save_content'])->name('save_content');
Route::post('save_content_regular',[AppsController::class,'save_content_regular'])->name('save_content_regular');
//Route::view('select_app','admin.content.select_app');
Route::get('select_app',[AppsController::class,'select_app'])->name('select_app');
Route::get('select_app_regular_content',[AppsController::class,'select_app_regular_content'])->name('select_app_regular_content');
Route::post('app_type_submit',[AppsController::class,'app_type_submit'])->name('app_type_submit');
Route::post('app_type_submit_regular_content',[AppsController::class,'app_type_submit_regular_content'])->name('app_type_submit_regular_content');
Route::get('edit_content/{id}',[AppsController::class,'edit_content'])->name('edit_content');
Route::post('update_content',[AppsController::class,'update_content'])->name('update_content');
Route::get('content_delete/{id}',[AppsController::class,'content_delete'])->name('content_delete');
Route::get('app_delete/{id}',[AppsController::class,'app_delete'])->name('app_delete');


//content end



Route::post('admin_login',[AppsController::class,'login'])->name('login');
Route::get('logout',[AppsController::class,'logout'])->name('logout');