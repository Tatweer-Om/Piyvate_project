   <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="http://Tatweersoft.om/" target="_blank">TatweerSoft</a> 2025</p>
            </div>
        </div>



    </div>

    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>


    <!-- Apex Chart -->
    <script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>

    <!-- Dashboard 1 -->
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <!-- clockpicker -->
    <script src="{{ asset('vendor/clockpicker/js/bootstrap-clockpicker.min.js') }}"></script>

    <!-- Clockpicker init -->
    <script src="{{ asset('js/plugins-init/clock-picker-init.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datetimepicker/js/moment.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- Material color picker -->
    <script src="{{ asset('vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    <!-- Material color picker init -->
    <script src="{{ asset('js/plugins-init/material-date-picker-init.js') }}"></script>
    <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>

    <script src="{{ asset('vendor/fullcalendar/js/main.min.js') }}"></script>

    <script src="{{ asset('js/plugins-init/fullcalendar-init.js') }}"></script>

    <script src="{{ asset('js/deznav-init.js') }}"></script>

    <script src="{{ asset('js/demo.js') }}"></script>
    <script src="{{ asset('js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
    <script src="{{ asset('vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('vendor/toastr/js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/plugins-init/toastr-init.js')}}"></script>
    <script src="{{  asset('js/custom.min.js')}}"></script>

    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>
    <script src="{{  asset('js/plugins-init/sweetalert.init.js')}}"></script>


<!-- All init script -->

	<script>
		function assignedDoctor(){

			/*  testimonial one function by = owl.carousel.js */
			jQuery('.assigned-doctor').owlCarousel({
				loop:false,
				margin:30,
				nav:true,
				autoplaySpeed: 3000,
				navSpeed: 3000,
				paginationSpeed: 3000,
				slideSpeed: 3000,
				smartSpeed: 3000,
				autoplay: false,
				rtl: true,
				dots: false,
				navText: ['<i class="fa fa-caret-left"></i>', '<i class="fa fa-caret-right"></i>'],
				responsive:{
					0:{
						items:1
					},
					576:{
						items:2
					},
					767:{
						items:3
					},
					991:{
						items:2
					},
					1200:{
						items:3
					},
					1600:{
						items:4
					},
					1920:{
						items:5
					}
				}
			})
		}

		jQuery(window).on('load',function(){
			setTimeout(function(){
				assignedDoctor();
			}, 1000);
		});

		function pieChart(){
			var data = {
				labels: ['35%', '55%', '10%'],
				series: [30, 25, 15]
			};
			var options = {
				labelInterpolationFnc: function(value) {
				  return value[0]
				}
			};
			var responsiveOptions = [
				['screen and (min-width: 230px)', {
					chartPadding: 10,
					donut: true,
					labelOffset: 40,
					donutWidth: 50,
					labelDirection: 'explode',
					labelInterpolationFnc: function(value) {
						return value;
					}
				}],
				['screen and (min-width: 230px)', {
					labelOffset: 60,
					chartPadding: 20
				}]
			];
			new Chartist.Pie('#pie-chart', data, options, responsiveOptions);
		}
		jQuery(window).on('load',function(){
			setTimeout(function(){
				pieChart();
			}, 1000);
		});

        (function($){
			var table = $('#example5').DataTable({
				searching: true,
				paging:true,
				select: true,
				lengthChange:false
			});
			$('#example tbody').on('click', 'tr', function () {
				var data = table.row( this ).data();
			});
		})(jQuery);




	</script>

@include('custom_js.custom_js')
@php

    $routeName = Route::currentRouteName();
    $segments = explode('.', $routeName);
    $route_name = isset($segments[0]) ? $segments[0] : null;

@endphp

    @if ($route_name == 'account')
        @include('custom_js.account_js')
        @elseif ($route_name == 'addproduct')
        @include('custom_js.purchase_js')
        @elseif ($route_name == 'qty_audit')
        @include('custom_js.qty_audit_js')
        @elseif ($route_name == 'purchases')
        @include('custom_js.show_purchase_js')

        @elseif ($route_name == 'products')
        @include('custom_js.product_js')
        @elseif ($route_name == 'category')
        @include('custom_js.category_js')
        @elseif ($route_name == 'govt')
        @include('custom_js.govt_agency_js')
        @elseif ($route_name == 'expense_category')
        @include('custom_js.expensecat_js')
		@elseif ($route_name == 'appointments')
        @include('custom_js.appointment_js')
        @elseif ($route_name == 'all_appointments')
        @include('custom_js.appointment_js')
        @elseif ($route_name == 'sessions_list')
        @include('custom_js.session_js')
        @elseif ($route_name == 'session_detail')
        @include('custom_js.session_js')
        @elseif ($route_name == 'supplier')
        @include('custom_js.supplier_js')
        @elseif ($route_name == 'expense')
        @include('custom_js.exepnse_js')
        @elseif ($route_name == 'role')
        @include('custom_js.role_js')
        @elseif ($route_name == 'speciality')
        @include('custom_js.doctor_speciality_js')
        @elseif ($route_name == 'doctor_list')
        @include('custom_js.doctor_js')
        @elseif ($route_name == 'department')
        @include('custom_js.department_js')
        @elseif ($route_name == 'staff_list')
        @include('custom_js.staff_js')
        @elseif ($route_name == 'staff_profile')
        @include('custom_js.staff_profile_js')
        @elseif ($route_name == 'user')
        @include('custom_js.user_js')
        @elseif ($route_name == 'setting')
        @include('custom_js.setting_js')
        @elseif ($route_name == 'branch')
        @include('custom_js.branch_js')
        @elseif ($route_name == 'edit_appointment')
        @include('custom_js.appointment_js')
        @elseif ($route_name == 'patient_list')
        @include('custom_js.patient_js')
        @elseif ($route_name == 'offer')
        @include('custom_js.offer_js')
        @elseif ($route_name == 'doctor_profile')
        @include('custom_js.doctor_profile_js')
        @elseif ($route_name == 'patient_profile')
        @include('custom_js.patient_profile_js')
        @elseif ($route_name == 'all_sessions')
        @include('custom_js.session_js')
        @elseif ($route_name == 'sation')
        @include('custom_js.sation_js')
        @elseif ($route_name == 'ministry_category')
        @include('custom_js.ministrycat_js')
        @elseif ($route_name == 'setting')
        @include('custom_js.setting_js')
        @elseif ($route_name == 'view_fee_card')
        @include('custom_js.setting_js')
        @elseif ($route_name == 'payroll')
        @include('custom_js.payroll_js')
        @elseif ($route_name == 'leaves')
        @include('custom_js.leaves_js')
        @elseif ($route_name == 'pending_leaves')
        @include('custom_js.pending_leaves_js')
        @elseif ($route_name == 'responded_leaves')
        @include('custom_js.responded_leaves_js')
        @elseif ($route_name == 'purchase_view')
        @include('custom_js.show_purchase_js')
        @elseif ($route_name == 'voucher')
        @include('custom_js.add_voucher_js')
        @elseif ($route_name == 'session_data')
        @include('custom_js.session_data_js')

        @elseif ($route_name == 'patient_session')
        @include('custom_js.patient_session_js')
        @elseif ($route_name == 'govt_detail')
        @include('custom_js.govt_detail_js')
        @elseif ($route_name == 'all_balance')
        @include('custom_js.balance_js')
        @elseif ($route_name == 'patient_appointment')
        @include('custom_js.patient_appointment_js')
        @elseif ($route_name == 'calender')
        @include('custom_js.calender_js')


    @endif

</body>

</html>
