<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReprintController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExpensecatController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\GovtController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/switch-language/{locale}', [HomeController::class, 'switchLanguage'])->name('switch_language');




//PatientController

Route::get('patient_list', [PatientController::class, 'patient_list'])->name('patient_list');
Route::get('patient_profile', [PatientController::class, 'patient_profile'])->name('patient_profile');


//doctorController

Route::get('doctor_list', [DoctorController::class, 'doctor_list'])->name('doctor_list');
Route::get('doctor_profile', [DoctorController::class, 'doctor_profile'])->name('doctor_profile');

//staffController

Route::get('staff_list', [StaffController::class, 'staff_list'])->name('staff_list');
Route::get('staff_profile', [StaffController::class, 'staff_profile'])->name('staff_profile');

//appointmentController

Route::get('appointments', [AppointmentController::class, 'appointments'])->name('appointments');


//SettingController

Route::get('setting', [SettingController::class, 'setting'])->name('setting');
Route::post('add_setting', [SettingController::class, 'add_setting'])->name('add_setting');



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
Route::post('get_maintenance_payment_data', [PosController::class, 'get_maintenance_payment_data']);
Route::post('get_maintenance_payment', [PosController::class, 'get_maintenance_payment']);
Route::post('add_maintenance_payment', [PosController::class, 'add_maintenance_payment']);
Route::get('pos_bill/{order_no}', [PosController::class, 'pos_bill'])->name('pos_bill');
Route::post('get_customer_data', [PosController::class, 'get_customer_data'])->name('get_customer_data');
Route::post('add_university', [PosController::class, 'add_university'])->name('add_university');
Route::post('add_workplace', [PosController::class, 'add_workplace'])->name('add_workplace');
Route::post('add_ministry', [PosController::class, 'add_ministry'])->name('add_ministry');
Route::get('make_profit', [PosController::class, 'make_profit'])->name('make_profit');
Route::get('bills/{order_no}', [PosController::class, 'bills'])->name('bills');



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

//GovtController
Route::get('govt', [GovtController::class, 'index'])->name('govt');
Route::post('add_govt', [GovtController::class, 'add_govt'])->name('add_govt');
Route::get('show_govt', [GovtController::class, 'show_govt'])->name('show_govt');
Route::post('edit_govt', [GovtController::class, 'edit_govt'])->name('edit_govt');
Route::post('update_govt', [GovtController::class, 'update_govt'])->name('update_govt');
Route::post('delete_govt', [GovtController::class, 'delete_govt'])->name('delete_govt');
