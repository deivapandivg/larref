<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\MenuGroupsController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\DesignationsController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\LeaveTypesController;
use App\Http\Controllers\QualificationsController;
use App\Http\Controllers\OfficeLocationsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\MediumsController;
use App\Http\Controllers\LeadStageController;
use App\Http\Controllers\CommunicationMediumController;
use App\Http\Controllers\LeadSubStageController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\CommunicationTypeController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\LeadSourceController;
use App\Http\Controllers\LeadSubSourceController;
use App\Http\Controllers\TimeLinesController;
use App\Http\Controllers\AdNamesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\LeadsAjaxController;
use App\Http\Controllers\ServiceCategoriesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\QuotationsController;
use App\Http\Controllers\RecruitmentStagesController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\RecruitmentAjaxController;
use App\Http\Controllers\QuotationsAjaxController;
use App\Http\Controllers\InvoiceAjaxController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProformaInvoiceAjaxController;
use App\Http\Controllers\ProformaInvoiceController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\CustomFieldsController;
use App\Http\Controllers\LeadsUploadsController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\LeadApiController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TicketCreatedTypeController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TicketsAjaxController;
use App\Http\Controllers\UserShiftsController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TaskBoardController;
use App\Http\Controllers\CalendarsController;
use App\Http\Controllers\TestQueueEmails;
use App\Http\Controllers\LeadsReportController;
use App\Http\Controllers\TemplatesController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\TestMailController;


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

/* Welcome Page */

Route::get('/', function () {
    return view('auth.login');
});




/* Leads Uploads  */

Route::get('leads_import', [LeadsController::class, 'leads_import'])->name('leads_import');

Route::post('leads_import_file_fetch/{selectData?}', [LeadsUploadsController::class, 'leads_import_file_fetch'])->name('leads_import_file_fetch');

// Route::post('leads_import_submit', [LeadsUploadsController::class, 'leads_import_submit'])->name('leads_import_submit');

Route::post('import_submit',[LeadsUploadsController::class,'import_submit'])->name('import_submit');





/* Dashboard && Attendance Log (Checkin & Checkout) && Common Delete */

Route::get('/dashboard', [AttendanceController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::post('dashboard_chart_counts',[AttendanceController::class,'dashboard_chart_counts'])->name('dashboard_chart_counts');

Route::post('attendance',[AttendanceController::class,'attendance'])->name('attendance');

Route::get('attendance_log', [AttendanceController::class, 'attendance_log'])->name('attendance_log');

Route::get('attendance_view/{attendance_id}', [AttendanceController::class, 'attendance_view'])->name('attendance_view');

Route::post('delete',[DeleteController::class,'delete'])->name('delete');



require __DIR__.'/auth.php';



/* User-Profile */

Route::get('user_profile_edit',[UserProfileController::class,'user_profile_edit'])->name('user_profile_edit');

Route::post('user_theme_submit',[UserProfileController::class,'user_theme_submit'])->name('user_theme_submit');

Route::post('password_chage_submit',[UserProfileController::class,'password_chage_submit'])->name('password_chage_submit');



/* Accounts */

Route::get('application_list',[ApplicationController::class,'application_list'])->name('application_list');

Route::get('app_account_add',[ApplicationController::class,'app_account_add'])->name('app_account_add');

Route::post('app_account_submit', [ApplicationController::class, 'app_account_submit'])->name('app_account_submit');

Route::get('app_account_edit/{app_account_id}', [ApplicationController::class, 'app_account_edit'])->name('app_account_edit');



/* Menu Groups */

Route::get('menu_groups',[MenuGroupsController::class,'menu_groups'])->name('menu_groups');

Route::post('menu_groups_submit',[MenuGroupsController::class,'menu_groups_submit'])->name('menu_groups_submit');

Route::get('menu_group_edit/{menu_group_id}', [MenuGroupsController::class, 'menu_group_edit'])->name('menu_group_edit');



/* Menus */

Route::get('menus',[MenusController::class,'menus'])->name('menus');

Route::post('menus_submit',[MenusController::class,'menus_submit'])->name('menus_submit');

Route::get('menus_edit/{menu_id}', [MenusController::class, 'menus_edit'])->name('menus_edit');



/* Employees */

Route::get('employees',[EmployeesController::class,'employees'])->name('employees');

Route::get('employees_add',[EmployeesController::class,'employees_add'])->name('employees_add');

Route::get('employees_edit/{id}',[EmployeesController::class,'employees_edit'])->name('employees_edit');

Route::get('employees_view/{id}',[EmployeesController::class,'employees_view'])->name('employees_view');

Route::post('employees_submit',[EmployeesController::class,'employees_submit'])->name('employees_submit');



/* Departments */

Route::get('departments',[DepartmentsController::class,'departments'])->name('departments');

Route::post('departments_submit',[DepartmentsController::class,'departments_submit'])->name('departments_submit');

Route::get('departments_edit/{department_id}', [DepartmentsController::class, 'departments_edit'])->name('departments_edit');



/* Teams */

Route::get('teams',[TeamsController::class,'teams'])->name('teams');

Route::post('teams_submit',[TeamsController::class,'teams_submit'])->name('teams_submit');

Route::get('teams_edit/{team_id}', [TeamsController::class, 'teams_edit'])->name('teams_edit');



/* Leave Types */

Route::get('leave_types',[LeaveTypesController::class,'leave_types'])->name('leave_types');

Route::post('leave_types_submit',[LeaveTypesController::class,'leave_types_submit'])->name('leave_types_submit');

Route::get('leave_types_edit/{leave_type_id}', [LeaveTypesController::class, 'leave_types_edit'])->name('leave_types_edit');



/* Qualifications */

Route::get('qualifications',[QualificationsController::class,'qualifications'])->name('qualifications');

Route::post('qualifications_submit',[QualificationsController::class,'qualifications_submit'])->name('qualifications_submit');

Route::get('qualifications_edit/{qualification_id}', [QualificationsController::class, 'qualifications_edit'])->name('qualifications_edit');



/* Office Locations / Branches */

Route::get('office_locations',[OfficeLocationsController::class,'office_locations'])->name('office_locations');

Route::post('office_locations_submit',[OfficeLocationsController::class,'office_locations_submit'])->name('office_locations_submit');

Route::get('office_locations_edit/{office_location_id}', [OfficeLocationsController::class, 'office_locations_edit'])->name('office_locations_edit');



/* Countries, States and Cities */

Route::get('country_id/{country_id}',[UserController::class,'country_id'])->name('country_id');

Route::get('state_id/{state_id}',[UserController::class,'state_id'])->name('state_id');

Route::get('employees_edit/{country_id}',[UserController::class,'country_id'])->name('country_id');

Route::get('employees_edit/{state_id}',[UserController::class,'state_id'])->name('state_id');

Route::get('shift_id/{shift_id}',[UserController::class,'shift_id'])->name('shift_id');

Route::get('employees_edit/{shift_id}',[UserController::class,'shift_id'])->name('shift_id');



/* Recruitments */

Route::get('recruitments',[RecruitmentController::class,'recruitments'])->name('recruitments');

Route::post('recruitment_submit',[RecruitmentController::class,'recruitment_submit'])->name('recruitment_submit');

Route::get('candidate_edit/{candidate_id}',[RecruitmentController::class, 'candidate_edit'])->name('candidate_edit');

Route::get('candidate_add',[RecruitmentController::class,'candidate_add'])->name('candidate_add');

Route::get('candidate_view/{candidate_id}',[RecruitmentController::class, 'candidate_view'])->name('candidate_view');

Route::post('recruitment_process_submit',[RecruitmentController::class,'recruitment_process_submit'])->name('recruitment_process_submit');



/* Notifications */

Route::get('notifications',[NotificationsController::class,'notifications'])->name('notifications');

Route::post('notification_viewed',[NotificationsController::class,'notification_viewed'])->name('notification_viewed');

Route::post('notification_all_read',[NotificationsController::class,'notification_all_read'])->name('notification_all_read');

Route::post('get_notifications_popup',[NotificationsController::class,'get_notifications_popup'])->name('get_notifications_popup');

Route::post('get_remainders',[NotificationsController::class,'get_remainders'])->name('get_remainders');

Route::post('get_remainder_later',[NotificationsController::class,'get_remainder_later'])->name('get_remainder_later');



/* Designations */

Route::get('designations',[DesignationsController::class,'designations'])->name('designations');

Route::get('designation_add',[DesignationsController::class,'designation_add'])->name('designation_add');

Route::post('designation_submit',[DesignationsController::class,'designation_submit'])->name('designation_submit');

Route::get('designation_edit/{designation_id}',[DesignationsController::class,'designation_edit'])->name('designation_edit');

Route::post('designation_edit_submit',[DesignationsController::class,'designation_edit_submit'])->name('designation_edit_submit');



/* Campaign */
Route::get('campaigns',[CampaignsController::class,'campaigns'])->name('campaigns');

Route::post('campaign_submit',[CampaignsController::class,'campaign_submit'])->name('campaign_submit');

Route::get('campaign_edit/{campaign_id}', [CampaignsController::class, 'campaign_edit'])->name('campaign_edit');



/* Mediums */

Route::get('mediums', [MediumsController::class, 'mediums'])->name('mediums');

Route::post('medium_submit', [MediumsController::class, 'medium_submit'])->name('medium_submit');

Route::get('medium_edit/{medium_id}',[MediumsController::class, 'medium_edit'])->name('medium_edit');



/* LeadStage */

Route::get('lead_stages', [LeadStageController::class, 'lead_stages'])->name('lead_stages');

Route::post('lead_stages_submit', [LeadStageController::class, 'lead_stages_submit'])->name('lead_stages_submit');

Route::get('lead_stage_edit/{lead_stage_id}', [LeadStageController::class, 'lead_stage_edit'])->name('lead_stage_edit');



/* Communication Mediums */

Route::get('communication_mediums', [CommunicationMediumController::class, 'communication_mediums'])->name('communication_mediums');

Route::post('communication_mediums_submit', [CommunicationMediumController::class, 'communication_mediums_submit'])->name('communication_mediums_submit');

Route::get('communication_medium_edit/{communication_medium_id}',[CommunicationMediumController::class, 'communication_medium_edit'])->name('communication_medium_edit');



/* LeadSubStage */

Route::get('lead_sub_stages', [LeadSubStageController::class, 'lead_sub_stages'])->name('lead_sub_stages');

Route::post('lead_sub_stages_submit', [LeadSubStageController::class, 'lead_sub_stages_submit'])->name('lead_sub_stages_submit');

Route::get('lead_sub_stage_edit/{lead_sub_stage_id}', [LeadSubStageController::class, 'lead_sub_stage_edit'])->name('lead_sub_stage_edit');

/* LeadApi */

Route::get('lead_api_list',[LeadApiController::class,'lead_api_list'])->name('lead_api_list');

Route::post('lead_api_submit',[LeadApiController::class,'lead_api_submit'])->name('lead_api_submit');

Route::get('lead_api_edit/{lead_api_id}',[LeadApiController::class,'lead_api_edit'])->name('lead_api_edit');

/* Leads */

Route::any('leads', [LeadsController::class, 'leads'])->name('leads');

Route::get('lead_add', [LeadsController::class, 'lead_add'])->name('lead_add');

Route::get('lead_edit/{lead_id}', [LeadsController::class, 'lead_edit'])->name('lead_edit');

Route::get('lead_view/{lead_id}', [LeadsController::class, 'lead_view'])->name('lead_view');

Route::get('sms_view/{lead_id}', [LeadsController::class, 'sms_view'])->name('sms_view');

Route::get('mail_view/{lead_id}', [LeadsController::class, 'mail_view'])->name('mail_view');

Route::get('whatsapp_view/{lead_id}', [LeadsController::class, 'whatsapp_view'])->name('whatsapp_view');

Route::post('leads_modal_view/{lead_id}',[LeadsController::class,'leads_modal_view'])->name('leads_modal_view');

Route::post('leads_submit', [LeadsController::class, 'leads_submit'])->name('leads_submit');

Route::post('leads_quick_add', [LeadsController::class, 'leads_quick_add'])->name('leads_quick_add');

Route::post('leads_quick_add_submit', [LeadsController::class, 'leads_quick_add_submit'])->name('leads_quick_add_submit');

// Route::get('lead_timeline_view/{lead_id}', [LeadsController::class, 'lead_timeline_view'])->name('lead_timeline_view');




/* Leads_ajax_DropDown Dependant */

Route::post('product_ajax', [LeadsController::class, 'product_ajax'])->name('product_ajax');

Route::post('state_ajax', [LeadsController::class, 'state_ajax'])->name('state_ajax');

Route::post('city_ajax', [LeadsController::class, 'city_ajax'])->name('city_ajax');

Route::post('lead_substage_ajax', [LeadsController::class, 'lead_substage_ajax'])->name('lead_substage_ajax');

Route::post('source_ajax', [LeadsController::class, 'source_ajax'])->name('source_ajax');

Route::post('communication_type_ajax', [LeadsController::class, 'communication_type_ajax'])->name('communication_type_ajax');

Route::post('communication_type_ajax_task', [LeadsController::class, 'communication_type_ajax_task'])->name('communication_type_ajax_task');

Route::post('lead_stage_ajax', [LeadsController::class, 'lead_stage_ajax'])->name('lead_stage_ajax');

Route::get('timeline_add/{lead_id}', [LeadsController::class, 'timeline_add'])->name('timeline_add');

Route::get('remainder_task_add/{RadioButton}', [LeadsController::class, 'remainder_task_add'])->name('remainder_task_add');

Route::post('remainder_task_submit',[LeadsController::class,'remainder_task_submit'])->name('remainder_task_submit');




/* Leads_ajax */

Route::post('leads_ajax', [LeadsAjaxController::class, 'leads_ajax'])->name('leads_ajax');

Route::get('users_get', [LeadsAjaxController::class, 'users_get'])->name('users_get');



/* Recruitment Ajax */

Route::post('recruitment_ajax', [RecruitmentAjaxController::class, 'recruitment_ajax'])->name('recruitment_ajax');



/* Communication Type */

Route::get('communication_types',[CommunicationTypeController::class, 'communication_types'])->name('communication_types');

Route::post('communication_type_submit', [CommunicationTypeController::class, 'communication_type_submit'])->name('communication_type_submit');

Route::get('communication_type_edit/{communication_type_id}', [CommunicationTypeController::class, 'communication_type_edit'])->name('communication_type_edit');



/* Product Categories */

Route::get('product_categories', [ProductCategoriesController::class, 'product_categories'])->name('product_categories');

Route::post('product_categories_submit', [ProductCategoriesController::class, 'product_categories_submit'])->name('product_categories_submit');

Route::get('product_categories_edit/{product_category_id}', [ProductCategoriesController::class, 'product_categories_edit'])->name('product_categories_edit');



/* Lead sources */

Route::get('lead_sources',[LeadSourceController::class,'lead_sources'])->name('lead_sources');

Route::post('lead_sources_submit',[LeadSourceController::class,'lead_sources_submit'])->name('lead_sources_submit');

Route::get('lead_sources_edit/{lead_source_id}', [LeadSourceController::class, 'lead_sources_edit'])->name('lead_sources_edit');



/* Lead Sub Sources */

Route::get('lead_sub_sources',[LeadSubSourceController::class,'lead_sub_sources'])->name('lead_sub_sources');

Route::post('lead_sub_sources_submit',[LeadSubSourceController::class,'lead_sub_sources_submit'])->name('lead_sub_sources_submit');

Route::get('lead_sub_sources_edit/{lead_sub_source_id}', [LeadSubSourceController::class, 'lead_sub_sources_edit'])->name('lead_sub_sources_edit');



/* TimeLine */

Route::get('timelines', [TimeLinesController::class, 'timelines'])->name('timelines');

Route::get('timeline_list',[TimeLinesController::class, 'timeline_list'])->name('timeline_list');

Route::post('timeline_submit',[TimeLinesController::class,'timeline_submit'])->name('timeline_submit');



/* Ad Names */

Route::get('ad_names',[AdNamesController::class,'ad_names'])->name('ad_names');

Route::post('ad_names_submit',[AdNamesController::class,'ad_names_submit'])->name('ad_names_submit');

Route::get('ad_names_edit/{ad_name_id}', [AdNamesController::class, 'ad_names_edit'])->name('ad_names_edit');



/* Products */

Route::get('products',[ProductsController::class,'products'])->name('products');

Route::get('products_add',[ProductsController::class,'products_add'])->name('products_add');

Route::post('products_submit',[ProductsController::class,'products_submit'])->name('products_submit');

Route::get('products_edit/{product_id}', [ProductsController::class, 'products_edit'])->name('products_edit');

Route::get('products_view/{product_id}', [ProductsController::class, 'products_view'])->name('products_view');

Route::post('products_modal_view/{product_id}', [ProductsController::class, 'products_modal_view'])->name('products_modal_view');



/* Service Categories */

Route::get('service_categories', [ServiceCategoriesController::class, 'service_categories'])->name('service_categories');

Route::post('service_categories_submit', [ServiceCategoriesController::class, 'service_categories_submit'])->name('service_categories_submit');

Route::get('service_categories_edit/{service_category_id}', [ServiceCategoriesController::class, 'service_categories_edit'])->name('service_categories_edit');



/* Services */

Route::get('services',[ServicesController::class,'services'])->name('services');

Route::get('services_add',[ServicesController::class,'services_add'])->name('services_add');

Route::post('services_submit',[ServicesController::class,'services_submit'])->name('services_submit');

Route::get('services_edit/{service_id}', [ServicesController::class, 'services_edit'])->name('services_edit');

Route::get('services_view/{service_id}', [ServicesController::class, 'services_view'])->name('services_view');

Route::post('services_modal_view/{service_id}', [ServicesController::class, 'services_modal_view'])->name('services_modal_view');



/* Clients */

Route::get('clients',[ClientsController::class,'clients'])->name('clients');

Route::get('client_add',[ClientsController::class,'client_add'])->name('client_add');

Route::post('clients_submit',[ClientsController::class,'clients_submit'])->name('clients_submit');

Route::get('clients_edit/{client_id}', [ClientsController::class, 'clients_edit'])->name('clients_edit');

Route::get('clients_view/{client_id}', [ClientsController::class, 'clients_view'])->name('clients_view');

Route::post('clients_modal_view/{client_id}', [ClientsController::class, 'clients_modal_view'])->name('clients_modal_view');



/* Quotations */

Route::get('quotations',[QuotationsController::class,'quotations'])->name('quotations');

Route::get('quotations_add',[QuotationsController::class,'quotations_add'])->name('quotations_add');

Route::get('quotations_edit/{quotation_id}',[QuotationsController::class,'quotations_edit'])->name('quotations_edit');

Route::get('quotations_view/{quotation_id}',[QuotationsController::class,'quotations_view'])->name('quotations_view');

Route::get('quotations_pdf/{quotation_id}',[QuotationsController::class,'quotations_pdf'])->name('quotations_pdf');

Route::post('quotations_submit',[QuotationsController::class,'quotations_submit'])->name('quotations_submit');

Route::get('quotation_send_mail/{quotation_id}',[QuotationsController::class,'quotation_send_mail'])->name('quotation_send_mail');



/* Quotations Ajax */

Route::post('quotation_new_product',[QuotationsAjaxController::class,'quotation_new_product'])->name('quotation_new_product');

Route::post('quotation_new_gst',[QuotationsAjaxController::class,'quotation_new_gst'])->name('quotation_new_gst');

Route::post('quotation_new_rate',[QuotationsAjaxController::class,'quotation_new_rate'])->name('quotation_new_rate');

Route::post('quotation_new_gst_service',[QuotationsAjaxController::class,'quotation_new_gst_service'])->name('quotation_new_gst_service');

Route::post('quotation_new_rate_service',[QuotationsAjaxController::class,'quotation_new_rate_service'])->name('quotation_new_rate_service');



/* Recruitment Stages */

Route::get('recruitment_stages', [RecruitmentStagesController::class, 'recruitment_stages'])->name('recruitment_stages');

Route::post('recruitment_stages_submit', [RecruitmentStagesController::class, 'recruitment_stages_submit'])->name('recruitment_stages_submit');

Route::get('recruitment_stages_edit/{recruitment_stage_id}', [RecruitmentStagesController::class, 'recruitment_stages_edit'])->name('recruitment_stages_edit');



/* Invoices */

Route::get('invoices',[InvoiceController::class,'invoices'])->name('invoices');

Route::get('invoices_add',[InvoiceController::class,'invoices_add'])->name('invoices_add');

Route::get('invoices_edit/{invoice_id}',[InvoiceController::class,'invoices_edit'])->name('invoices_edit');

Route::get('invoices_view/{invoice_id}',[InvoiceController::class,'invoices_view'])->name('invoices_view');

Route::get('invoices_pdf/{invoice_id}',[InvoiceController::class,'invoices_pdf'])->name('invoices_pdf');

Route::post('invoices_submit',[InvoiceController::class,'invoices_submit'])->name('invoices_submit');



/* Invoices Ajax */

Route::post('invoice_new_product',[InvoiceAjaxController::class,'invoice_new_product'])->name('invoice_new_product');

Route::post('invoice_new_gst',[InvoiceAjaxController::class,'invoice_new_gst'])->name('invoice_new_gst');

Route::post('invoice_new_rate',[InvoiceAjaxController::class,'invoice_new_rate'])->name('invoice_new_rate');

Route::post('invoice_new_gst_service',[InvoiceAjaxController::class,'invoice_new_gst_service'])->name('invoice_new_gst_service');

Route::post('invoice_new_rate_service',[InvoiceAjaxController::class,'invoice_new_rate_service'])->name('invoice_new_rate_service');

Route::get('invoice_send_mail/{invoice_id}',[InvoiceController::class,'invoice_send_mail'])->name('invoice_send_mail');


/* Proforma Invoices */

Route::get('proforma_invoices',[ProformaInvoiceController::class,'proforma_invoices'])->name('proforma_invoices');

Route::get('proforma_invoices_add',[ProformaInvoiceController::class,'proforma_invoices_add'])->name('proforma_invoices_add');

Route::get('proforma_invoices_edit/{proforma_invoice_id}',[ProformaInvoiceController::class,'proforma_invoices_edit'])->name('proforma_invoices_edit');

Route::get('proforma_invoices_view/{proforma_invoice_id}',[ProformaInvoiceController::class,'proforma_invoices_view'])->name('proforma_invoices_view');

Route::get('proforma_invoices_pdf/{proforma_invoice_id}',[ProformaInvoiceController::class,'proforma_invoices_pdf'])->name('proforma_invoices_pdf');

Route::post('proforma_invoices_submit',[ProformaInvoiceController::class,'proforma_invoices_submit'])->name('proforma_invoices_submit');

Route::get('proforma_invoice_send_mail/{proforma_invoice_id}',[ProformaInvoiceController::class,'proforma_invoice_send_mail'])->name('proforma_invoice_send_mail');



/* Proforma Invoices Ajax */

Route::post('proforma_invoice_new_product',[ProformaInvoiceAjaxController::class,'proforma_invoice_new_product'])->name('proforma_invoice_new_product');

Route::post('proforma_invoice_new_gst',[ProformaInvoiceAjaxController::class,'proforma_invoice_new_gst'])->name('proforma_invoice_new_gst');

Route::post('proforma_invoice_new_rate',[ProformaInvoiceAjaxController::class,'proforma_invoice_new_rate'])->name('proforma_invoice_new_rate');

Route::post('proforma_invoice_new_gst_service',[ProformaInvoiceAjaxController::class,'proforma_invoice_new_gst_service'])->name('proforma_invoice_new_gst_service');

Route::post('proforma_invoice_new_rate_service',[ProformaInvoiceAjaxController::class,'proforma_invoice_new_rate_service'])->name('proforma_invoice_new_rate_service');



/* Mail */

Route::get('view_mail',[MailController::class,'view_mail'])->name('view_mail');

Route::post('send_mail',[MailController::class,'send_mail'])->name('send_mail');



/* Vendors */

Route::get('vendors',[VendorsController::class,'vendors'])->name('vendors');

Route::get('vendor_add',[VendorsController::class,'vendor_add'])->name('vendor_add');

Route::post('vendors_submit',[VendorsController::class,'vendors_submit'])->name('vendors_submit');

Route::get('vendors_edit/{vendor_id}', [VendorsController::class, 'vendors_edit'])->name('vendors_edit');

Route::get('vendors_view/{vendor_id}', [VendorsController::class, 'vendors_view'])->name('vendors_view');

Route::post('vendors_modal_view/{vendor_id}', [VendorsController::class, 'vendors_modal_view'])->name('vendors_modal_view');



/* Petty Cash */

Route::get('pettycash',[PettyCashController::class,'pettycash'])->name('pettycash');

Route::get('pettycash_edit/{pettycash_id}',[PettyCashController::class,'pettycash_edit'])->name('pettycash_edit');

Route::post('pettycash_modal_view/{pettycash_id}',[PettyCashController::class,'pettycash_modal_view'])->name('pettycash_modal_view');

Route::post('pettycash_submit',[PettyCashController::class,'pettycash_submit'])->name('pettycash_submit');



/* Petty Cash Top Up */

Route::get('pettycash_topup',[PettyCashController::class,'pettycash_topup'])->name('pettycash_topup');

Route::get('pettycash_topup_edit/{topup_id}',[PettyCashController::class,'pettycash_topup_edit'])->name('pettycash_topup_edit');

Route::post('pettycash_topup_submit',[PettyCashController::class,'pettycash_topup_submit'])->name('pettycash_topup_submit');



/* Custom Fields */

Route::get('custom_fields',[CustomFieldsController::class,'custom_fields'])->name('custom_fields');
Route::get('custom_fields_add',[CustomFieldsController::class,'custom_fields_add'])->name('custom_fields_add');

Route::get('custom_fields_edit/{custom_field_id}',[CustomFieldsController::class,'custom_fields_edit'])->name('custom_fields_edit');

Route::post('custom_fields_dropdown',[CustomFieldsController::class,'custom_fields_dropdown'])->name('custom_fields_dropdown');

Route::post('custom_fields_radio',[CustomFieldsController::class,'custom_fields_radio'])->name('custom_fields_radio');

Route::post('custom_fields_checkbox',[CustomFieldsController::class,'custom_fields_checkbox'])->name('custom_fields_checkbox');

Route::post('custom_field_submit',[CustomFieldsController::class,'custom_field_submit'])->name('custom_field_submit');



/* Leaves Applied & Approvals*/

Route::get('leave_applied',[LeavesController::class,'leave_applied'])->name('leave_applied');

Route::get('leave_approvals',[LeavesController::class,'leave_approvals'])->name('leave_approvals');

Route::get('leave_applied_edit/{leave_applied_id}',[LeavesController::class,'leave_applied_edit'])->name('leave_applied_edit');

Route::get('leave_applied_status_view/{leave_applied_id}',[LeavesController::class,'leave_applied_status_view'])->name('leave_applied_status_view');

Route::get('leave_approval_model/{leave_approval_id}',[LeavesController::class,'leave_approval_model'])->name('leave_approval_model');

Route::post('leave_applied_submit',[LeavesController::class,'leave_applied_submit'])->name('leave_applied_submit');

Route::post('leave_approval_submit',[LeavesController::class,'leave_approval_submit'])->name('leave_approval_submit');

Route::post('leave_applied_send_ajax',[LeavesController::class,'leave_applied_send_ajax'])->name('leave_applied_send_ajax');

Route::post('leave_applied_ajax',[LeavesController::class,'leave_applied_ajax'])->name('leave_applied_ajax');

Route::post('leave_approval_ajax',[LeavesController::class,'leave_approval_ajax'])->name('leave_approval_ajax');



/* Tasks */

Route::get('tasks',[TasksController::class,'tasks'])->name('tasks');

Route::get('task_edit/{task_id}',[TasksController::class,'task_edit'])->name('task_edit');

Route::get('task_update/{task_id}',[TasksController::class,'task_update'])->name('task_update');

Route::post('tasks_submit',[TasksController::class,'tasks_submit'])->name('tasks_submit');

Route::post('task_update_submit',[TasksController::class,'task_update_submit'])->name('task_update_submit');

Route::post('client_change_ajax',[TasksController::class,'client_change_ajax'])->name('client_change_ajax');

Route::get('task_view/{task_id}',[TasksController::class,'task_view'])->name('task_view');

Route::post('task_modal_view/{task_id}',[TasksController::class,'task_modal_view'])->name('task_modal_view');

Route::post('tasks_ajax',[TasksController::class,'tasks_ajax'])->name('tasks_ajax');

Route::get('tasks_manager',[TasksController::class,'tasks_manager'])->name('tasks_manager');



/* Task Status */

Route::get('task_status',[TaskStatusController::class,'task_status'])->name('task_status');

Route::post('status_submit',[TaskStatusController::class,'status_submit'])->name('status_submit');

Route::get('status_edit/{status_id}', [TaskStatusController::class, 'status_edit'])->name('status_edit');



/* Ticket Created Types */

Route::get('ticket_created_type',[TicketCreatedTypeController::class,'ticket_created_type'])->name('ticket_created_type');

Route::post('ticket_created_type_submit',[TicketCreatedTypeController::class,'ticket_created_type_submit'])->name('ticket_created_type_submit');

Route::get('ticket_created_type_edit/{ticket_created_type_id}', [TicketCreatedTypeController::class, 'ticket_created_type_edit'])->name('ticket_created_type_edit');



/* Projects */

Route::get('projects',[ProjectsController::class,'projects'])->name('projects');

Route::post('project_submit',[ProjectsController::class,'project_submit'])->name('project_submit');

Route::get('project_edit/{project_id}', [ProjectsController::class, 'project_edit'])->name('project_edit');



/* Tickets */

Route::get('tickets',[TicketsController::class,'tickets'])->name('tickets');

Route::get('ticket_add',[TicketsController::class,'ticket_add'])->name('ticket_add');

Route::post('ticket_submit',[TicketsController::class,'ticket_submit'])->name('ticket_submit');

Route::get('ticket_edit/{ticket_id}', [TicketsController::class, 'ticket_edit'])->name('ticket_edit');

Route::get('ticket_view/{ticket_id}', [TicketsController::class, 'ticket_view'])->name('ticket_view');

Route::post('ticket_modal_view/{ticket_id}', [TicketsController::class, 'ticket_modal_view'])->name('ticket_modal_view');

Route::post('client_change_ajax',[TicketsController::class,'client_change_ajax'])->name('client_change_ajax');

Route::post('timelines_ticket_submit',[TicketsController::class,'timelines_ticket_submit'])->name('timelines_ticket_submit');

Route::post('chat_submit',[TicketsController::class,'chat_submit'])->name('chat_submit');

Route::post('tickets_ajax',[TicketsAjaxController::class,'tickets_ajax'])->name('tickets_ajax');



/* User Shifts */

Route::get('users_shifts',[UserShiftsController::class,'users_shifts'])->name('users_shifts');

Route::get('shift_edit/{users_shift_id}', [UserShiftsController::class, 'shift_edit'])->name('shift_edit');

Route::get('shift_view/{users_shift_id}', [UserShiftsController::class, 'shift_view'])->name('shift_view');

Route::post('shift_submit',[UserShiftsController::class,'shift_submit'])->name('shift_submit');


/*Renewal */

Route::get('renewals',[RenewalController::class,'renewals'])->name('renewals');

Route::post('renewals_submit',[RenewalController::class,'renewals_submit'])->name('renewals_submit');

Route::get('renewal_edit/{renewal_id}', [RenewalController::class, 'renewal_edit'])->name('renewal_edit');


/* Reports*/

Route::get('employees_report',[ReportsController::class,'employees_report'])->name('employees_report');

Route::post('report_chart_counts',[ReportsController::class,'report_chart_counts'])->name('report_chart_counts');

Route::get('daily_report_send_mail',[ReportsController::class,'daily_report_send_mail'])->name('daily_report_send_mail');


/* Search */

Route::get('search_model',[AttendanceController::class,'search_model'])->name('search_model');


/* Task Board */

Route::get('taskboard',[TaskBoardController::class,'taskboard'])->name('taskboard');
Route::get('tasksboard',[TaskBoardController::class,'tasksboard'])->name('tasksboard');


/* Calendar */

Route::get('calendar',[CalendarsController::class,'calendar'])->name('calendar');

Route::post('calendar_append/{reminder}',[CalendarsController::class,'calendar_append'])->name('calendar_append');

Route::post('calendar_model_ajax',[CalendarsController::class,'calendar_model_ajax'])->name('calendar_model_ajax');

Route::post('reminder_followup_submit',[CalendarsController::class,'reminder_followup_submit'])->name('reminder_followup_submit');

Route::post('calendar_task_add/{RadioButton}', [CalendarsController::class, 'calendar_task_add'])->name('calendar_task_add');

Route::get('sending-queue-emails', [TestQueueEmails::class,'sendTestEmails'])->name('sendTestEmails');


/* Leads Report */

Route::get('leads_report', [LeadsReportController::class,'leads_report'])->name('leads_report');

Route::post('leads_report_ajax', [LeadsReportController::class,'leads_report_ajax'])->name('leads_report_ajax');

/*sms*/

Route::get('sms', [TemplatesController::class,'sms'])->name('sms');

Route::get('sms_add', [TemplatesController::class,'sms_add'])->name('sms_add');

Route::post('sms_submit', [TemplatesController::class,'sms_submit'])->name('sms_submit');

Route::get('sms_edit/{template_id}', [TemplatesController::class,'sms_edit'])->name('sms_edit');


/*mail*/

Route::get('mail', [TemplatesController::class,'mail'])->name('mail');

Route::get('mail_add', [TemplatesController::class,'mail_add'])->name('mail_add');

Route::post('mail_submit', [TemplatesController::class,'mail_submit'])->name('mail_submit');

Route::get('mail_edit/{template_id}', [TemplatesController::class,'mail_edit'])->name('mail_edit');

Route::post('send_mail_smtp', [SendMailController::class,'send_mail_smtp']);   



/*whatsapp*/

Route::get('whatsapp', [TemplatesController::class,'whatsapp'])->name('whatsapp');

Route::get('whatsapp_add', [TemplatesController::class,'whatsapp_add'])->name('whatsapp_add');

Route::post('whatsapp_submit', [TemplatesController::class,'whatsapp_submit'])->name('whatsapp_submit');

Route::get('whatsapp_edit/{template_id}', [TemplatesController::class,'whatsapp_edit'])->name('whatsapp_edit');


/*Global Options*/

Route::get('global_options', [TemplatesController::class,'global_options'])->name('global_options');

Route::post('global_options_submit', [TemplatesController::class,'global_options_submit'])->name('global_options_submit');



/* Lead Options */

Route::get('sms_option/{lead_id}', [TemplatesController::class,'sms_option'])->name('sms_option');

Route::post('sms_option_submit', [TemplatesController::class,'sms_option_submit'])->name('sms_option_submit');

Route::get('mail_option/{lead_id}', [TemplatesController::class,'mail_option'])->name('mail_option');

Route::post('mail_option_submit', [TemplatesController::class,'mail_option_submit'])->name('mail_option_submit');

Route::get('whatsapp_option/{lead_id}', [TemplatesController::class,'whatsapp_option'])->name('whatsapp_option');

Route::post('whatsapp_option_submit', [TemplatesController::class,'whatsapp_option_submit'])->name('whatsapp_option_submit');


