<script>

    $(document).ready(function() {
        $('#add_payroll_modal').on('hidden.bs.modal', function() {
            $(".add_payroll")[0].reset();
        });
    
        
        $('#all_payroll').DataTable({
            "sAjaxSource": "{{ url('show_employee_payroll') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true, 
        });
    
        $('#all_payroll_data').DataTable({
            "sAjaxSource": "{{ url('show_employee_payroll_data') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
          
        });
          
    
        $('#add_payroll_modal').off().on('submit', function (e) {
            e.preventDefault();
            var formdatas = new FormData($('.add_payroll')[0]);
            formdatas.append('_token', '{{ csrf_token() }}'); 
            var amount = $('.amount').val(); 
            var employee_id = $('#employee_id').val(); 
            if (amount == "") {
                show_notification('error', '<?php echo trans('messages.provide_amount_lang',[],session('locale')); ?>');
                return false;
            }
            if (employee_id == "") {
                show_notification('error', '<?php echo trans('messages.provide_employee_lang',[],session('locale')); ?>');
                return false;
            }
            showPreloader();
            before_submit();
            $.ajax({
                type: "POST",
                url: "{{ url('add_payroll') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function (data) {
                    hidePreloader();
                    after_submit();
                    show_notification('success','<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                    );
                    $('#add_payroll_modal').modal('hide');
                    $('#all_payroll').DataTable().ajax.reload();
                    $('#all_payroll_data').DataTable().ajax.reload();
                    $(".add_payroll")[0].reset();
                },
                error: function (data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                    );
                    $('#all_payroll').DataTable().ajax.reload();
                    $('#all_payroll_data').DataTable().ajax.reload();
                }
            });
        });
    
    });
             
     
    
    
    
    
    function del_payroll (id) {
        Swal.fire({
            title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
            // text:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (result) {
            if (result.value) {
                $('#global-loader').show();
                before_submit();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: "{{ url('delete_payroll') }}",
                    type: 'POST',
                    data: {id: id,_token: csrfToken},
                    error: function () {
                        $('#global-loader').hide();
                        after_submit();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                    },
                    success: function (data) {
                        $('#global-loader').hide();
                        after_submit();
                        $('#all_payroll').DataTable().ajax.reload();
                        $('#all_payroll_data').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
            }
        });
    }
    
    </script>
    