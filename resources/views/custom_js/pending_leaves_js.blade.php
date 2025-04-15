<script>
    $(document).ready(function() {
        $('#add_leave_response_modal').on('hidden.bs.modal', function() {
            $(".add_leave_response")[0].reset();
        });
    
        
        $('#all_pending_leaves').DataTable({
            "sAjaxSource": "{{ url('show_pending_leaves') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true, 
        });
    
         
    
            
    
        $('.add_leave_response').off().on('submit', function (e) {
            e.preventDefault();
            var formdatas = new FormData($('.add_leave_response')[0]);
            formdatas.append('_token', '{{ csrf_token() }}'); 
            var response_type = $('.response_type').val(); 
            if (response_type == 3) {
                if($('.reason').val()=="")
                {   
                    show_notification('error', '<?php echo trans('messages.provide_reason_lang',[],session('locale')); ?>');
                    return false;
                }
            }
            
            showPreloader();
            before_submit();
            $.ajax({
                type: "POST",
                url: "{{ url('add_leaves_reponse') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function (data) {
                    hidePreloader();
                    after_submit();
                    show_notification('success','<?php echo trans('messages.data_add_response_lang',[],session('locale')); ?>'
                    );
                    $('#add_leave_response_modal').modal('hide');
                    $('#all_pending_leaves').DataTable().ajax.reload(); 
                    $(".add_leave_response")[0].reset();
                },
                error: function (data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error','<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                    );
                    $('#all_pending_leaves').DataTable().ajax.reload(); 
                }
            });
        });
    
    });
    function leave_response_type(response_type,leave_id)
    {
        $('.response_type').val(response_type); 
        $('.leave_id').val(leave_id); 
    }
                
        
    
    
    
    
     
    
     
    
    
    </script>
        