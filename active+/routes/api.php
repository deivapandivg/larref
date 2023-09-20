<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register',[AuthController::class, 'register']);

Route::post('login',[AuthController::class, 'login']);

Route::get('user_list',[AuthController::class, 'user_details']);

Route::post('user_edits',[AuthController::class, 'user_details_update']);

Route::post('user_profile_edits',[AuthController::class, 'user_details_profile_update']);

Route::get('attendance_list',[AuthController::class, 'attendance_list']);

Route::get('get_states',[AuthController::class, 'get_states']);

Route::get('get_cities',[AuthController::class, 'get_cities']);

Route::get('get_countries',[AuthController::class, 'get_countries']);

Route::post('check_in',[AuthController::class, 'check_in']);

Route::post('check_out',[AuthController::class, 'check_out']);

Route::get('checkin_status',[AuthController::class, 'checkin_status']);

Route::get('dashboard_counts',[AuthController::class, 'dashboard_counts']);

Route::get('task_list',[ApiController::class, 'task_list']);

Route::get('task_filter',[ApiController::class, 'task_filter']);

Route::get('task_update',[AuthController::class, 'task_update']);

Route::post('task_update_submit',[AuthController::class, 'task_update_submit']);

Route::post('leave_approval_add',[ApiController::class, 'leave_approval_add']);

Route::get('leave_approval_list',[ApiController::class, 'leave_approval_list']);

Route::post('forget_password',[AuthController::class, 'forget_password']);

Route::post('password_update',[AuthController::class, 'password_update']);

Route::get('lead_details',[AuthController::class, 'lead_details']);

Route::get('lead_list',[AuthController::class, 'lead_list']);

Route::get('lead_filter',[AuthController::class, 'lead_filter']);

Route::post('lead_create',[AuthController::class, 'lead_create']);

Route::post('lead_update',[AuthController::class, 'lead_update']);

Route::get('get_designations',[AuthController::class,'get_designations']);

Route::get('get_lead_stage',[AuthController::class,'get_lead_stage']);

Route::get('get_lead_sub_stage',[AuthController::class,'get_lead_sub_stage']);

Route::get('lead_edit',[AuthController::class,'lead_edit']);

Route::post('lead_update',[AuthController::class,'lead_update']);

Route::get('get_products',[AuthController::class,'get_products']);

Route::get('get_sub_source',[AuthController::class,'get_sub_source']);

Route::get('attendance_status',[AuthController::class,'attendance_status']);

Route::get('ticket_lists',[ApiController::class,'ticket_lists']);

Route::get('ticket_filter',[ApiController::class,'ticket_filter']);

Route::get('ticket_view',[AuthController::class,'ticket_view']);

Route::post('add_server_key',[AuthController::class,'add_server_key']);

Route::post('timeline_add',[AuthController::class,'timeline_add']);

Route::get('get_communication_medium',[AuthController::class,'get_communication_medium']);

Route::get('get_communication_type',[AuthController::class,'get_communication_type']);

Route::get('get_task_select_values', [AuthController::class,'get_task_select_values'])->name('get_task_select_values');

Route::get('get_ticket_select_values', [AuthController::class,'get_ticket_select_values'])->name('get_ticket_select_values');

Route::post('task_add', [AuthController::class,'task_add'])->name('task_add');

Route::post('ticket_add', [AuthController::class,'ticket_add'])->name('ticket_add');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('logout',[AuthController::class, 'logout']);
});

Route::post('ticket_update_submit', [AuthController::class,'ticket_update_submit'])->name('ticket_update_submit');

Route::get('quotations_list', [AuthController::class,'quotations_list'])->name('quotations_list');

Route::post('timeline_add', [AuthController::class,'timeline_add'])->name('timeline_add');

Route::post('audio_store', [AuthController::class,'audio_store'])->name('audio_store');

Route::post('expense_add', [ApiController::class,'expense_add'])->name('expense_add');

// Route::post('expense_file', [ApiController::class,'expense_file'])->name('expense_file');

Route::post('expense_edit', [ApiController::class,'expense_edit'])->name('expense_edit');

Route::post('expense_delete', [ApiController::class,'expense_delete'])->name('expense_delete');

Route::get('pettycash_expense_list', [ApiController::class,'pettycash_expense_list'])->name('pettycash_expense_list');

Route::get('quotation_list', [ApiController::class,'quotation_list'])->name('quotation_list');

Route::get('quote_gst', [ApiController::class,'quote_gst'])->name('quote_gst');

Route::get('quote_rate', [ApiController::class,'quote_rate'])->name('quote_rate');

Route::post('quote_add', [ApiController::class,'quote_add'])->name('quote_add');

Route::get('get_quote', [ApiController::class,'get_quote'])->name('get_quote');

Route::post('quote_edit', [ApiController::class,'quote_edit'])->name('quote_edit');

Route::get('invoice_list', [ApiController::class,'invoice_list'])->name('invoice_list');

Route::post('invoice_add', [ApiController::class,'invoice_add'])->name('invoice_add');

Route::get('get_invoice', [ApiController::class,'get_invoice'])->name('get_invoice');

Route::post('invoice_edit', [ApiController::class,'invoice_edit'])->name('invoice_edit');

Route::get('proforma_invoice_list', [ApiController::class,'proforma_invoice_list'])->name('proforma_invoice_list');

Route::post('proforma_invoice_add', [ApiController::class,'proforma_invoice_add'])->name('proforma_invoice_add');

Route::get('get_proforma_invoice', [ApiController::class,'get_proforma_invoice'])->name('get_proforma_invoice');

Route::post('proforma_invoice_edit', [ApiController::class,'proforma_invoice_edit'])->name('proforma_invoice_edit');

Route::post('website_lead_insert',[AuthController::class,'website_lead_insert'])->name('website_lead_insert');

Route::post('facebook_leads',[AuthController::class,'facebook_leads'])->name('facebook_leads');

Route::get('domain_details',[ApiController::class,'domain_details'])->name('domain_details');


Route::get('users',[UserController::class,'index']);

Route::get('user_edit/{id}',[UserController::class,'edit']);

Route::post('user_submit',[UserController::class,'store']);

Route::post('user_update/{id}',[UserController::class,'update']);

Route::delete('user_delete/{id}',[UserController::class,'destroy']);






