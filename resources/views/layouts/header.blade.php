<?php
	$locale = session('locale');

	if($locale=="ar")
	{

		$class='rtl';
	}
	else
	{
		$class='ltr';

	}
?>
<!DOCTYPE html>
<html lang="en" >

<head>

    <!-- Title -->
    <title>Piyvate</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">




    <!-- Mobile Specific -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

    <link href="{{ asset('vendor/fullcalendar/css/main.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/css/toastr.min.css') }}">
    <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <!-- Material color picker -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ url('css/style.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('css/style-rtl.css') }}" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">




</head>

<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">

        <div class="nav-header">
            <a href="{{ url('/') }}" class="brand-logo">
                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                    <image href="{{ asset('images/logo/piyalogo-1.png') }}" width="200" height="200" />
                </svg>


            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                PIYVATE
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">

                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
                                    <i id="icon-light" class="fas fa-sun"></i>
                                    <i id="icon-dark" class="fas fa-moon"></i>

                                </a>
                            </li>
                            <li class="nav-item dropdown notification_dropdown">
                                @if($locale == 'ar')
                                    <a class="nav-link" href="{{ route('switch_language', ['locale' => 'en']) }}">
                                        <img src="{{ asset('flags/us.png') }}" class="me-1" height="12">
                                    </a>
                                @else
                                    <a class="nav-link" href="{{ route('switch_language', ['locale' => 'ar']) }}">
                                        <img src="{{ asset('flags/om.png') }}" class="me-1" height="12">
                                    </a>
                                @endif
                            </li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:;" role="button" data-bs-toggle="dropdown">
                                    <img src="{{  url('images/profile/12.png')}}" width="20" alt="image">
                                    <div class="header-info">
                                        <span>Hello,<strong> Haseeb</strong></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="app-profile.html" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <span class="ms-2">Profile </span>
                                    </a>
                                    <a href="email-inbox.html" class="dropdown-item ai-icon">
                                        <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                            </path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                        <span class="ms-2">Inbox </span>
                                    </a>
                                    <a href="{{ route('logout') }}" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12">
                                            </line>
                                        </svg>
                                        <span class="ms-2">{{ trans('messages.logout') }}</span>
                                    </a>

                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">
                    <li>
                        <a class="ai-icon" href="{{ route('home') }}" aria-expanded="false">
                            <i class="flaticon-381-home"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('pos') }}" class="ai-icon" aria-expanded="false">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="nav-text">POS</span>
                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-user"></i>
                            <span class="nav-text">Patients</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('patient_list') }}">Patients</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-settings-1"></i>
                            <span class="nav-text">Session Settings</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('sation') }}">Add Setting</a></li>
                            <li><a href="{{ url('govt') }}">Govt Agencies</a></li>
                            <li><a href="{{ url('ministry_category') }}">Ministry Categories</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-briefcase"></i>
                            <span class="nav-text">HR</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('payroll') }}">Payroll</a></li>
                            <li><a href="{{ url('leaves') }}">Leaves</a></li>
                            <li><a href="{{ url('pending_leaves') }}">Pending Leaves</a></li>
                            <li><a href="{{ url('responded_leaves') }}">Responded Leaves</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-heart"></i>
                            <span class="nav-text">Doctors</span>
                        </a>

                        <ul aria-expanded="false">
                            <li><a href="{{ url('doctor_list') }}">Doctors</a></li>
                            <li><a href="{{ url('speciality') }}">Doctors Specialities</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-user-9"></i>
                            <span class="nav-text">Staff</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('staff_list') }}">Staff List</a></li>
                            <li><a href="{{ url('role') }}">Staff Role</a></li>
                            <li><a href="{{ url('department') }}">Department</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-user-1"></i>
                            <span class="nav-text">Users</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('user') }}">Add User</a></li>
                            <li><a href="{{ url('branch') }}">Branches</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-calendar"></i>
                            <span class="nav-text">Appointments</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('voucher') }}">Add Vouchers</a></li>
                            <li><a href="{{ url('appointments') }}">Add Appointment</a></li>
                            <li><a href="{{ url('all_appointments') }}">All Appointments</a></li>
                            <li><a href="{{ url('all_sessions') }}">Direct Session Bookings</a></li>
                            <li><a href="{{ url('session_data') }}">All Sessions Data</a></li>
                            <li><a href="{{ url('sessions_list') }}">Add Sessions</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-box"></i>
                            <span class="nav-text">Stock</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('supplier') }}">Supplier</a></li>
                            <li><a href="{{ url('category') }}">Product Category</a></li>
                            <li><a href="{{ url('products') }}">Products</a></li>
                            <li><a href="{{ url('qty_audit') }}">Qty Audit</a></li>
                            <li><a href="{{ url('purchases') }}">Purchases</a></li>
                            <li><a href="{{ url('addproduct') }}">Add Purchase</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="flaticon-381-settings"></i>
                            <span class="nav-text">Settings</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('setting') }}">Company Profile</a></li>
                            <li><a href="{{ url('view_fee_card') }}">Appointment Fee</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="fas fa-money-bill-wave"></i>
                            <span class="nav-text">Expense</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('expense_category') }}">Expense Category</a></li>
                            <li><a href="{{ url('account') }}">Account</a></li>
                            <li><a href="{{ url('expense') }}">Expense</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                            <i class="fas fa-tags"></i>
                            <span class="nav-text">Offers</span>
                        </a>
                        <ul aria-expanded="false">
                            <li><a href="{{ url('offer') }}">Add Offer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <!--**********************************
            Sidebar end
        ***********************************-->
        @yield('main')
