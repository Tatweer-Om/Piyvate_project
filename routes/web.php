<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\GovtController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SationController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReprintController;
use App\Http\Controllers\SessionCONTROLLER;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExpensecatController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ClinicalNotesController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\VoucherController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/switch-language/{locale}', [HomeController::class, 'switchLanguage'])->name('switch_language');




//PatientController

Route::get('patient_list', [PatientController::class, 'patient_list'])->name('patient_list');
Route::get('patient_profile/{id}', [PatientController::class, 'patient_profile'])->name('patient_profile');
Route::get('patient_session/{id}', [PatientController::class, 'patient_session'])->name('patient_session');

Route::get('show_patient', [PatientController::class, 'show_patient'])->name('show_patient');
Route::post('add_patient', [PatientController::class, 'add_patient'])->name('add_patient');
Route::post('update_patient', [PatientController::class, 'update_patient'])->name('update_patient');
Route::post('edit_patient', [PatientController::class, 'edit_patient'])->name('edit_patient');
Route::post('delete_patient', [PatientController::class, 'delete_patient'])->name('delete_patient');
Route::post('edit_ind_session', [PatientController::class, 'edit_ind_session'])->name('edit_ind_session');
Route::post('update_ind_session', [PatientController::class, 'update_ind_session'])->name('update_ind_session');

Route::post('transfer_ind_session', [PatientController::class, 'transfer_ind_session'])->name('transfer_ind_session');
Route::post('update_transfer_ind_session', [PatientController::class, 'update_transfer_ind_session'])->name('update_transfer_ind_session');


Route::get('/patient/{id}/appointments-and-sessions', [PatientController::class, 'getAppointmentsAndSessions']);
// routes/web.php
Route::get('download-file/{file_id}', [PatientController::class, 'download'])->name('file.download');


// routes/web.php
Route::get('/patient/{id}/appointments', [PatientController::class, 'getAppointments']);
Route::get('/patient/{id}/appointmentsdetail', [PatientController::class, 'appointmentsdetail']);

Route::get('/patient/{id}/sessions', [PatientController::class, 'getSessions']);
Route::get('/patient/{id}/payments', [PatientController::class, 'getPayments']);
Route::get('show_all_sessions_by_patient', [PatientController::class, 'show_all_sessions_by_patient'])->name('show_all_sessions_by_patient');
Route::get('show_all_payment_by_patient', [PatientController::class, 'show_all_payment_by_patient'])->name('show_all_payment_by_patient');
Route::post('submit_contract_payment', [PatientController::class, 'submit_contract_payment'])->name('submit_contract_payment');

Route::post('/save_prescription', [PatientController::class, 'save_prescription'])->name('save_prescription');
Route::post('/lab_reports_upload', [PatientController::class, 'lab_reports_upload'])->name('lab_reports_upload');

// HRcontroller
Route::get('payroll', [HrController::class, 'payroll'])->name('payroll');
Route::get('show_employee_payroll', [HrController::class, 'show_employee_payroll'])->name('show_employee_payroll');
Route::get('show_employee_payroll_data', [HrController::class, 'show_employee_payroll_data'])->name('show_employee_payroll_data');
Route::post('add_payroll', [HrController::class, 'add_payroll'])->name('add_payroll');
Route::post('delete_payroll', [HrController::class, 'delete_payroll'])->name('delete_payroll');
Route::get('/download-payroll/{filename}', [HrController::class, 'downloadPayroll'])->name('download_payroll');

Route::get('leaves', [HrController::class, 'leaves'])->name('leaves');
Route::get('show_employee_leaves', [HrController::class, 'show_employee_leaves'])->name('show_employee_leaves');
Route::get('show_employee_leaves_data', [HrController::class, 'show_employee_leaves_data'])->name('show_employee_leaves_data');
Route::post('add_leaves', [HrController::class, 'add_leaves'])->name('add_leaves');
Route::post('delete_leaves', [HrController::class, 'delete_leaves'])->name('delete_leaves');
Route::post('get_remaining_leaves', [HrController::class, 'get_remaining_leaves'])->name('get_remaining_leaves');
Route::get('/download-leaves/{filename}', [HrController::class, 'downloadleaves'])->name('download_leaves');
Route::get('pending_leaves', [HrController::class, 'pending_leaves'])->name('pending_leaves');
Route::get('show_pending_leaves', [HrController::class, 'show_pending_leaves'])->name('show_pending_leaves');
Route::post('add_leaves_reponse', [HrController::class, 'add_leaves_reponse'])->name('add_leaves_reponse');
Route::get('responded_leaves', [HrController::class, 'responded_leaves'])->name('responded_leaves');
Route::get('show_responded_leaves', [HrController::class, 'show_responded_leaves'])->name('show_responded_leaves');


//staffController

Route::get('staff_list', [StaffController::class, 'staff_list'])->name('staff_list');
Route::get('staff_profile/{id}', [StaffController::class, 'staff_profile'])->name('staff_profile');


//appointmentController

Route::get('appointments', [AppointmentController::class, 'appointments'])->name('appointments');
Route::get('show_appointment', [AppointmentController::class, 'show_appointment'])->name('show_appointment');
Route::post('add_appointment', [AppointmentController::class, 'add_appointment'])->name('add_appointment');
Route::post('update_appointment', [AppointmentController::class, 'update_appointment'])->name('update_appointment');
Route::post('cancel_appointment', [AppointmentController::class, 'cancel_appointment'])->name('cancel_appointment');

Route::get('edit_appointment/{id}', [AppointmentController::class, 'edit_appointment'])->name('edit_appointment');
Route::get('all_appointments', [AppointmentController::class, 'all_appointments'])->name('all_appointments');
Route::get('sessions_list', [AppointmentController::class, 'sessions_list'])->name('sessions_list');
Route::post('/get-session-price', [AppointmentController::class, 'getSessionPrice'])->name('get.session.price');
Route::get('/getMinistryDetails/{id}', [AppointmentController::class, 'getMinistryDetails'])->name('getMinistryDetails');
Route::get('/getsessionDetails/{id}', [AppointmentController::class, 'getsessionDetails'])->name('getsessionDetails');
Route::get('/getOfferDetails/{id}', [AppointmentController::class, 'getOfferDetails'])->name('getOfferDetails');
Route::get('/get-session-data/{appointment_id}', [AppointmentController::class, 'getSessionData']);
Route::post('/save_sessions', [AppointmentController::class, 'save_sessions'])->name('save_sessions');
Route::post('/save_session_payment', [AppointmentController::class, 'save_session_payment'])->name('save_session_payment');
Route::get('/search-patient', [AppointmentController::class, 'searchPatient']);





//SettingController





//userController
Route::get('user', [UserController::class, 'index'])->name('user');
Route::post('add_user', [UserController::class, 'add_user'])->name('add_user');
Route::get('show_user', [UserController::class, 'show_user'])->name('show_user');
Route::post('edit_user', [UserController::class, 'edit_user'])->name('edit_user');
Route::post('update_user', [UserController::class, 'update_user'])->name('update_user');
Route::post('delete_user', [UserController::class, 'delete_user'])->name('delete_user');
Route::get('login_page', [UserController::class, 'login_page'])->name('login_page');
Route::post('login', [UserController::class, 'login'])->name('login');
Route::get('logout', [UserController::class, 'logout'])->name('logout');


//Branchcontroller

Route::get('branch', [BranchController::class, 'index'])->name('branch');
Route::post('add_branch', [BranchController::class, 'add_branch'])->name('add_branch');
Route::get('show_branch', [BranchController::class, 'show_branch'])->name('show_branch');
Route::post('edit_branch', [BranchController::class, 'edit_branch'])->name('edit_branch');
Route::post('update_branch', [BranchController::class, 'update_branch'])->name('update_branch');
Route::post('delete_branch', [BranchController::class, 'delete_branch'])->name('delete_branch');


//offercontroller

Route::get('offer', [OfferController::class, 'index'])->name('offer');
Route::post('add_offer', [OfferController::class, 'add_offer'])->name('add_offer');
Route::get('show_offer', [OfferController::class, 'show_offer'])->name('show_offer');
Route::post('edit_offer', [OfferController::class, 'edit_offer'])->name('edit_offer');
Route::post('update_offer', [OfferController::class, 'update_offer'])->name('update_offer');
Route::post('delete_offer', [OfferController::class, 'delete_offer'])->name('delete_offer');


//offercontroller

Route::post('add_session', [SessionCONTROLLER::class, 'add_session'])->name('add_session');
Route::get('show_sessions', [SessionCONTROLLER::class, 'show_sessions'])->name('show_sessions');
Route::get('all_sessions', [SessionCONTROLLER::class, 'all_sessions'])->name('all_sessions');


Route::post('add_session_detail', [SessionCONTROLLER::class, 'add_session_detail'])->name('add_session_detail');
Route::post('save_session_payment2', [SessionCONTROLLER::class, 'save_session_payment2'])->name('save_session_payment2');


Route::get('session_detail/{id}', [SessionCONTROLLER::class, 'session_detail'])->name('session_detail');
Route::get('/session_detail2/{id}', [SessionCONTROLLER::class, 'session_detail2'])->name('session_detail2');
Route::get('/search_patient', [SessionCONTROLLER::class, 'search_patient'])->name('search_patient');
Route::post('check_voucher', [SessionCONTROLLER::class, 'check_voucher'])->name('check_voucher');
Route::get('session_data', [SessionCONTROLLER::class, 'session_data'])->name('session_data');
Route::get('show_session_data', [SessionCONTROLLER::class, 'show_session_data'])->name('show_session_data');
Route::post('edit_ind_session2', [SessionCONTROLLER::class, 'edit_ind_session2'])->name('edit_ind_session2');
Route::post('update_ind_session2', [SessionCONTROLLER::class, 'update_ind_session2'])->name('update_ind_session2');


Route::get('sation', [SationController::class, 'index'])->name('sation');
Route::match(['get', 'post'], 'add_sation', [SationController::class, 'add_sation'])->name('add_sation');
Route::get('show_sation', [SationController::class, 'show_sation'])->name('show_sation');
Route::post('edit_sation', [SationController::class, 'edit_sation'])->name('edit_sation');
Route::post('update_sation', [SationController::class, 'update_sation'])->name('update_sation');
Route::post('delete_sation', [SationController::class, 'delete_sation'])->name('delete_sation');

// exepnsecat
Route::get('expense_category', [ExpensecatController::class, 'index'])->name('expense_category');
Route::post('add_expense_category', [ExpensecatController::class, 'add_expense_category'])->name('add_expense_category');
Route::get('show_expense_category', [ExpensecatController::class, 'show_expense_category'])->name('show_expense_category');
Route::post('edit_expense_category', [ExpensecatController::class, 'edit_expense_category'])->name('edit_expense_category');
Route::post('update_expense_category', [ExpensecatController::class, 'update_expense_category'])->name('update_expense_category');
Route::post('delete_expense_category', [ExpensecatController::class, 'delete_expense_category'])->name('delete_expense_category');

// expense_categoryController Routes

Route::get('expense', [ExpenseController::class, 'index'])->name('expense');
Route::post('add_expense', [ExpenseController::class, 'add_expense'])->name('add_expense');
Route::get('show_expense', [ExpenseController::class, 'show_expense'])->name('show_expense');
Route::post('edit_expense', [ExpenseController::class, 'edit_expense'])->name('edit_expense');
Route::post('update_expense', [ExpenseController::class, 'update_expense'])->name('update_expense');
Route::post('delete_expense', [ExpenseController::class, 'delete_expense'])->name('delete_expense_category');
Route::get('download_expense_image/{id}', [ExpenseController::class, 'download_expense_image'])->name('download_expense_image');

// AccountController Routes

Route::get('account', [AccountController::class, 'index'])->name('account');
Route::post('add_account', [AccountController::class, 'add_account'])->name('add_account');
Route::get('show_account', [AccountController::class, 'show_account'])->name('show_account');
Route::post('edit_account', [AccountController::class, 'edit_account'])->name('edit_account');
Route::post('update_account', [AccountController::class, 'update_account'])->name('update_account');
Route::post('delete_account', [AccountController::class, 'delete_account'])->name('delete_account');

// roles
Route::get('role', [RoleController::class, 'index'])->name('role');
Route::post('add_role', [RoleController::class, 'add_role'])->name('add_role');
Route::get('show_role', [RoleController::class, 'show_role'])->name('show_role');
Route::post('edit_role', [RoleController::class, 'edit_role'])->name('edit_role');
Route::post('update_role', [RoleController::class, 'update_role'])->name('update_role');
Route::post('delete_role', [RoleController::class, 'delete_role'])->name('delete_role');

//staff

Route::get('employee', [StaffController::class, 'index'])->name('employee');
Route::post('add_employee', [StaffController::class, 'add_employee'])->name('add_employee');
Route::get('show_employee', [StaffController::class, 'show_employee'])->name('show_employee');
Route::post('edit_employee', [StaffController::class, 'edit_employee'])->name('edit_employee');
Route::post('update_employee', [StaffController::class, 'update_employee'])->name('update_employee');
Route::post('delete_employee', [StaffController::class, 'delete_employee'])->name('delete_employee');

// departments
Route::get('department', [DepartmentController::class, 'index'])->name('department');
Route::post('add_department', [DepartmentController::class, 'add_department'])->name('add_department');
Route::get('show_department', [DepartmentController::class, 'show_department'])->name('show_department');
Route::post('edit_department', [DepartmentController::class, 'edit_department'])->name('edit_department');
Route::post('update_department', [DepartmentController::class, 'update_department'])->name('update_department');
Route::post('delete_department', [DepartmentController::class, 'delete_department'])->name('delete_department');

//staff

Route::get('supplier', [SupplierController::class, 'index'])->name('supplier');
Route::post('add_supplier', [SupplierController::class, 'add_supplier'])->name('add_supplier');
Route::get('show_supplier', [SupplierController::class, 'show_supplier'])->name('show_supplier');
Route::post('edit_supplier', [SupplierController::class, 'edit_supplier'])->name('edit_supplier');
Route::post('update_supplier', [SupplierController::class, 'update_supplier'])->name('update_supplier');
Route::post('delete_supplier', [SupplierController::class, 'delete_supplier'])->name('delete_supplier');




// PurchaseController Routes
Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases');
Route::get('show_purchase', [PurchaseController::class, 'show_purchase'])->name('show_purchase');
Route::get('addproduct', [PurchaseController::class, 'product'])->name('addproduct');
Route::post('add_purchase_product', [PurchaseController::class, 'add_purchase_product'])->name('add_purchase_product');
Route::post('get_selected_new_data', [PurchaseController::class, 'get_selected_new_data'])->name('get_selected_new_data');
Route::post('search_invoice', [PurchaseController::class, 'search_invoice'])->name('search_invoice');
Route::get('search_barcode', [PurchaseController::class, 'search_barcode'])->name('search_barcode');
Route::post('get_product_data', [PurchaseController::class, 'get_product_data'])->name('get_product_data');
Route::post('delete_purchase', [PurchaseController::class, 'delete_purchase'])->name('delete_purchase');
Route::get('purchase_view/{invoice_no}', [PurchaseController::class, 'purchase_view'])->name('purchase_view');
Route::get('purchase_detail/{invoice_no}', [PurchaseController::class, 'purchase_view'])->name('purchase_view');
Route::post('check_imei_availability', [PurchaseController::class, 'check_imei_availability'])->name('check_imei_availability');
Route::post('get_purchase_payment', [PurchaseController::class, 'get_purchase_payment'])->name('get_purchase_payment');
Route::post('add_purchase_payment', [PurchaseController::class, 'add_purchase_payment'])->name('add_purchase_payment');
Route::get('purchase_invoice/{invoice_no}', [PurchaseController::class, 'purchase_invoice'])->name('purchase_invoice');
Route::post('get_purchase_products', [PurchaseController::class, 'get_purchase_products'])->name('get_purchase_products');
Route::get('edit_purchase/{id}', [PurchaseController::class, 'edit_purchase'])->name('edit_purchase');
Route::post('update_purchase', [PurchaseController::class, 'update_purchase'])->name('update_purchase');
Route::post('complete_purchase', [PurchaseController::class, 'complete_purchase'])->name('complete_purchase');
Route::post('check_tax_active', [PurchaseController::class, 'check_tax_active'])->name('check_tax_active');
Route::get('download-receipt/{filename}', [PurchaseController::class, 'downloadReceipt']);
Route::post('delete_purchase_payment', [PurchaseController::class, 'delete_purchase_payment'])->name('delete_purchase_payment');




// ProductController routes
Route::get('products', [ProductController::class, 'index'])->name('products');
Route::get('show_product', [ProductController::class, 'show_product'])->name('show_product');
Route::get('product_view/{id}', [ProductController::class, 'product_view'])->name('product_view');
Route::post('get_product_qty', [ProductController::class, 'get_product_qty'])->name('get_product_qty');
Route::post('add_damage_qty', [ProductController::class, 'add_damage_qty'])->name('add_damage_qty');
Route::post('undo_damage_product', [ProductController::class, 'undo_damage_product'])->name('undo_damage_product');
Route::post('add_undo_damage_qty', [ProductController::class, 'add_undo_damage_qty'])->name('add_undo_damage_qty');
Route::match(['get', 'post'], 'qty_audit', [ProductController::class, 'qty_audit'])->name('qty_audit');
Route::match(['get', 'post'], 'qty_audit', [ProductController::class, 'qty_audit'])->name('qty_audit');

Route::get('show_qty_audit', [ProductController::class, 'show_qty_audit'])->name('show_qty_audit');
Route::get('product_barcode/{id}', [ProductController::class, 'product_barcode'])->name('product_barcode');
Route::post('edit_product', [ProductController::class, 'edit_product'])->name('edit_product');
Route::post('update_product', [ProductController::class, 'update_product'])->name('update_product');
Route::post('delete_product', [ProductController::class, 'delete_product'])->name('delete_product');
Route::get('delete_imei', [ProductController::class, 'delete_imei'])->name('delete_imei');
Route::post('replace_pro_imei', [ProductController::class, 'replace_pro_imei'])->name('replace_pro_imei');
Route::post('add_replace_product', [ProductController::class, 'add_replace_product'])->name('add_replace_product');
Route::post('send_item_back', [ProductController::class, 'send_item_back'])->name('send_item_back');


//categorycontroller

Route::get('category', [CategoryController::class, 'index'])->name('category');
Route::post('add_category', [CategoryController::class, 'add_category'])->name('add_category');
Route::get('show_category', [CategoryController::class, 'show_category'])->name('show_category');
Route::post('edit_category', [CategoryController::class, 'edit_category'])->name('edit_category');
Route::post('update_category', [CategoryController::class, 'update_category'])->name('update_category');
Route::post('delete_category', [CategoryController::class, 'delete_category'])->name('delete_category');



//reprintController

Route::get('reprint', [ReprintController::class, 'index'])->name('reprint');
Route::get('show_order', [ReprintController::class, 'show_order']);
Route::get('delete_order/{order_no}', [ReprintController::class, 'delete_order']);
Route::get('a5_print/{order_no}', [ReprintController::class, 'a5_print'])->name('a5_print');

//poscontroller
Route::post('product_autocomplete', [PosController::class, 'product_autocomplete']);
Route::post('customer_autocomplete', [PosController::class, 'customer_autocomplete']);

Route::get('pos', [PosController::class, 'index'])->name('pos');
Route::post('cat_products', [PosController::class, 'cat_products']);
Route::post('order_list', [PosController::class, 'order_list']);

Route::post('add_pos_order', [PosController::class, 'add_pos_order']);
Route::get('order_reciept/{id}', [PosController::class, 'order_reciept']);
Route::post('fetch_product_imeis', [PosController::class, 'fetch_product_imeis']);
Route::post('get_pro_imei', [PosController::class, 'get_pro_imei']);
Route::post('check_imei', [PosController::class, 'check_imei']);
Route::post('check_barcode', [PosController::class, 'check_barcode']);
Route::post('get_return_items', [PosController::class, 'get_return_items']);
Route::post('add_replace_item', [PosController::class, 'add_replace_item']);
Route::post('get_restore_items', [PosController::class, 'get_restore_items']);
Route::post('add_restore_item', [PosController::class, 'add_restore_item']);
Route::post('get_product_type', [PosController::class, 'get_product_type']);
Route::post('add_pending_order', [PosController::class, 'add_pending_order']);
Route::match(['get', 'post'],'hold_orders', [PosController::class, 'hold_orders']);
Route::match(['get', 'post'],'get_hold_data', [PosController::class, 'get_hold_data']);

Route::get('pos_bill/{order_no}', [PosController::class, 'pos_bill'])->name('pos_bill');
Route::post('get_customer_data', [PosController::class, 'get_customer_data'])->name('get_customer_data');

Route::get('make_profit', [PosController::class, 'make_profit'])->name('make_profit');
Route::get('bills/{order_no}', [PosController::class, 'bills'])->name('bills');
Route::post('add_pos_patient', [PosController::class, 'add_pos_patient']);



//specialitycontroller

Route::get('speciality', [SpecialityController::class, 'index'])->name('speciality');
Route::post('add_speciality', [SpecialityController::class, 'add_speciality'])->name('add_speciality');
Route::get('show_speciality', [SpecialityController::class, 'show_speciality'])->name('show_speciality');
Route::post('edit_speciality', [SpecialityController::class, 'edit_speciality'])->name('edit_speciality');
Route::post('update_speciality', [SpecialityController::class, 'update_speciality'])->name('update_speciality');
Route::post('delete_speciality', [SpecialityController::class, 'delete_speciality'])->name('delete_speciality');


//DoctorCOntroller
Route::get('doctor', [DoctorController::class, 'index'])->name('doctor');
Route::post('add_doctor', [DoctorController::class, 'add_doctor'])->name('add_doctor');
Route::get('show_doctors', [DoctorController::class, 'show_doctors'])->name('show_doctors');
Route::post('edit_doctor', [DoctorController::class, 'edit_doctor'])->name('edit_doctor');
Route::post('update_doctor', [DoctorController::class, 'update_doctor'])->name('update_doctor');
Route::post('delete_doctor', [DoctorController::class, 'delete_doctor'])->name('delete_doctor');
Route::get('doctor_list', [DoctorController::class, 'doctor_list'])->name('doctor_list');
Route::get('show_doctor_patients', [DoctorController::class, 'show_doctor_patients'])->name('show_doctor_patients');
Route::get('doctor_profile/{id}', [DoctorController::class, 'doctor_profile'])->name('doctor_profile');
Route::get('show_all_sessions_by_doctor', [DoctorController::class, 'show_all_sessions_by_doctor'])->name('show_all_sessions_by_doctor');

Route::get('/doctor/{doctorId}/appointments', [DoctorController::class, 'getDoctorAppointments']);

//GovtController
Route::get('govt', [GovtController::class, 'index'])->name('govt');
Route::post('add_govt', [GovtController::class, 'add_govt'])->name('add_govt');
Route::get('show_govt', [GovtController::class, 'show_govt'])->name('show_govt');
Route::post('edit_govt', [GovtController::class, 'edit_govt'])->name('edit_govt');
Route::post('update_govt', [GovtController::class, 'update_govt'])->name('update_govt');
Route::post('delete_govt', [GovtController::class, 'delete_govt'])->name('delete_govt');


//settingcontroller
Route::get('setting', [SettingController::class, 'setting'])->name('setting');
Route::post('add_setting', [SettingController::class, 'add_setting'])->name('add_setting');
Route::get('view_fee_card', [SettingController::class, 'view_fee_card'])->name('view_fee_card');
Route::post('appointment_fee', [SettingController::class, 'appointment_fee'])->name('appointment_fee');
Route::get('setting_data', [SettingController::class, 'setting_data'])->name('setting_data');



Route::get('ministry_category', [MinistryController::class, 'index'])->name('ministry_category');
Route::post('add_ministry_category', [MinistryController::class, 'add_ministry_category'])->name('add_ministry_category');
Route::get('show_ministry_category', [MinistryController::class, 'show_ministry_category'])->name('show_ministry_category');
Route::post('edit_ministry_category', [MinistryController::class, 'edit_ministry_category'])->name('edit_ministry_category');
Route::post('update_ministry_category', [MinistryController::class, 'update_ministry_category'])->name('update_ministry_category');
Route::post('delete_ministry_category', [MinistryController::class, 'delete_ministry_category'])->name('delete_ministry_category');

//ClinicalNotes
Route::get('soap_ot/{id}', [ClinicalNotesController::class, 'soap_ot'])->name('soap_ot');
Route::get('soap_pt/{id}', [ClinicalNotesController::class, 'soap_pt'])->name('soap_pt');
Route::get('otatp_pedriatic/{id}', [ClinicalNotesController::class, 'otatp_pedriatic'])->name('otatp_pedriatic');
Route::get('neuro_pedriatic/{id}', [ClinicalNotesController::class, 'neuro_pedriatic'])->name('neuro_pedriatic');
Route::get('neuro_pedriatic_view/{id}', [ClinicalNotesController::class, 'neuro_pedriatic_view'])->name('neuro_pedriatic_view');
Route::post('/add_neuro_pedriatic', [ClinicalNotesController::class, 'add_neuro_pedriatic'])->name('add_neuro_pedriatic');
Route::post('/update_neuro_pedriatic/{id}', [ClinicalNotesController::class, 'update_neuro_pedriatic'])->name('update_neuro_pedriatic');

Route::get('physical_dysfunction/{id}', [ClinicalNotesController::class, 'physical_dysfunction'])->name('physical_dysfunction');
Route::post('add_physical_dysfunction', [ClinicalNotesController::class, 'add_physical_dysfunction'])->name('add_physical_dysfunction');
Route::get('edit_physical_dysfunction/{id}', [ClinicalNotesController::class, 'edit_physical_dysfunction'])->name('edit_physical_dysfunction');
Route::post('update_physical_dysfunction/{id}', [ClinicalNotesController::class, 'update_physical_dysfunction'])->name('update_physical_dysfunction');


Route::get('otatp_ortho/{id}', [ClinicalNotesController::class, 'otatp_ortho'])->name('otatp_ortho');
Route::post('add_otatp_ortho', [ClinicalNotesController::class, 'add_otatp_ortho'])->name('add_otatp_ortho');
Route::get('edit_otatp_ortho/{id}', [ClinicalNotesController::class, 'edit_otatp_ortho'])->name('edit_otatp_ortho');
Route::post('update_otatp_ortho/{id}', [ClinicalNotesController::class, 'update_otatp_ortho'])->name('update_otatp_ortho');

Route::post('/add_otp_pediatric', [ClinicalNotesController::class, 'add_otp_pediatric'])->name('add_otp_pediatric');
Route::get('/edit_otp_pediatric/{id}', [ClinicalNotesController::class, 'edit_otp_pediatric'])->name('edit_otp_pediatric');
Route::post('/update_otp_pediatric/{id}', [ClinicalNotesController::class, 'update_otp_pediatric'])->name('update_otp_pediatric');

Route::post('/add_soap_ot', [ClinicalNotesController::class, 'add_soap_ot'])->name('add_soap_ot');
Route::get('soap_ot_all/{id}', [ClinicalNotesController::class, 'soap_ot_all'])->name('soap_ot_all');
Route::post('update_soap_ot/{id}', [ClinicalNotesController::class, 'update_soap_ot'])->name('update_soap_ot');

Route::post('/add_soap_pt', [ClinicalNotesController::class, 'add_soap_pt'])->name('add_soap_pt');
Route::get('/soap_pt_all/{id}', [ClinicalNotesController::class, 'soap_pt_all'])->name('soap_pt_all');
Route::post('/update_soap_pt/{id}', [ClinicalNotesController::class, 'update_soap_pt'])->name('update_soap_pt');

Route::post('/add_otatp_ortho', [ClinicalNotesController::class, 'add_otatp_ortho'])->name('add_otatp_ortho');


//vouchercontroller

Route::get('voucher', [VoucherController::class, 'index'])->name('voucher');
Route::post('add_voucher', [VoucherController::class, 'add_voucher'])->name('add_voucher');
Route::get('show_voucher', [VoucherController::class, 'show_voucher'])->name('show_voucher');
Route::post('edit_voucher', [VoucherController::class, 'edit_voucher'])->name('edit_voucher');
Route::post('update_voucher', [VoucherController::class, 'update_voucher'])->name('update_voucher');
Route::post('delete_voucher', [VoucherController::class, 'delete_voucher'])->name('delete_voucher');



