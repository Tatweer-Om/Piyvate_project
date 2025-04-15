<script>
$(document).ready(function() {
    $('#add_leaves_modal').on('hidden.bs.modal', function() {
        $(".add_leaves")[0].reset();
    });

    
    $('#all_leaves').DataTable({
        "sAjaxSource": "{{ url('show_employee_leaves') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true, 
    });

    $('#all_leaves_data').DataTable({
        "ajax": {
            "url": "{{ url('show_employee_leaves_data') }}",
            "type": "GET", // or "POST" if your route expects POST
            "data": function (d) {
                d.employee_id = $('#employee_id').val(); // or any JS variable you want to pass
            }
        },
        "bFilter": true,
        "pagingType": "numbers",
        "ordering": true
    });

        

    $('#add_leaves_modal').off().on('submit', function (e) {
        e.preventDefault();
        var formdatas = new FormData($('.add_leaves')[0]);
        formdatas.append('_token', '{{ csrf_token() }}'); 
        var total_leaves = $('.total_leaves').val(); 
        var employee_id = $('#employee_id').val(); 
        if (total_leaves == "") {
            show_notification('error', '<?php echo trans('messages.provide_total_leaves_lang',[],session('locale')); ?>');
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
            url: "{{ url('add_leaves') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function (data) {
                hidePreloader();
                after_submit();
                show_notification('success','<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_leaves_modal').modal('hide');
                $('#all_leaves').DataTable().ajax.reload();
                $('#all_leaves_data').DataTable().ajax.reload();
                $(".add_leaves")[0].reset();
            },
            error: function (data) {
                hidePreloader();
                after_submit();
                show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_leaves').DataTable().ajax.reload();
                $('#all_leaves_data').DataTable().ajax.reload();
            }
        });
    });

});
            
    




function del_leaves (id) {
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
                url: "{{ url('delete_leaves') }}",
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
                    $('#all_leaves').DataTable().ajax.reload();
                    $('#all_leaves_data').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}

$('#employee_id, #leaves_type').on('change', function() {
    $('#all_leaves_data').DataTable().ajax.reload();
    $('#global-loader').show();
    
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    if ($('#employee_id').val() !== "" && $('#leaves_type').val() !== "") { 
        $.ajax({
            url: "{{ url('get_remaining_leaves') }}",
            method: "POST",
            data: {
                id: $('#employee_id').val(),
                leaves_type: $('#leaves_type').val(),
                _token: csrfToken
            },
            success: function(data) {
                $('#global-loader').hide();
                $('.remaining_leaves').val(data.remaining_leaves);
                $('.total_leaves').keyup();
            },
            error: function(data) {
                $('#global-loader').hide();
                after_submit(); // Make sure this is defined
                show_notification('error', '<?php echo trans('messages.process_failed_lang', [], session('locale')); ?>');
                console.log(data);
                return false;
            }
        });
    }
});

$('.total_leaves').on('keyup', function() {
    if($('#leaves_type').val()!=3)
    {    
        if($(this).val() > $('.remaining_leaves').val())
        {   
            show_notification('error', '<?php echo trans('messages.leaves_cant_greater_than_remaining_leaves_lang', [], session('locale')); ?>');
            $(this).val(0)
        }
    }
});


</script>
    