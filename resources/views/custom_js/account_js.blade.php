<script>
    $(document).ready(function() {
        $('#add_account_modal').on('hidden.bs.modal', function() {
            $(".add_account")[0].reset();
            $('.account_id').val('');
        });

        $('#all_accounts').DataTable({
            "sAjaxSource": "{{ url('show_account') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_account').submit(function(e) {

            e.preventDefault();

            var formdatas = new FormData($(this)[0]);
            formdatas.append('_token', '{{ csrf_token() }}');
            var title = $('.account_name').val();
            var id = $('.account_id').val();

            if (title === "") {
                show_notification('error',
                    '{{ trans('messages.add_account_name_lang', [], session('locale')) }}');
                return false;
            }

            showPreloader();
            before_submit();

            $.ajax({
                type: "POST",
                url: id ? "{{ url('update_account') }}" :
                    "{{ url('add_account') }}",
                data: formdatas,
                contentType: false,
                processData: false,
                success: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('success', id ?
                        '{{ trans('messages.data_update_success_lang', [], session('locale')) }}' :
                        '{{ trans('messages.data_add_success_lang', [], session('locale')) }}'
                    );
                    $('#add_account_modal').modal('hide');
                    $('#all_accounts').DataTable().ajax.reload();
                    if (!id) $(".add_account")[0].reset();
                },
                error: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error', id ?
                        '{{ trans('messages.data_update_failed_lang', [], session('locale')) }}' :
                        '{{ trans('messages.data_add_failed_lang', [], session('locale')) }}'
                    );
                    $('#all_accounts').DataTable().ajax.reload();
                    console.log(data);
                }
            });
        });


    });


    function del(id) {
            Swal.fire({
                title: '{{ trans('messages.sure_lang', [], session('locale')) }}',
                text: '{{ trans('messages.delete_lang', [], session('locale')) }}',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: '{{ trans('messages.delete_it_lang', [], session('locale')) }}',
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    showPreloader();
                    before_submit();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('delete_account') }}",
                        type: 'POST',
                        data: {
                            id: id,
                            _token: csrfToken
                        },
                        error: function() {
                            hidePreloader();
                            after_submit();
                            show_notification('error',
                                '{{ trans('messages.delete_failed_lang', [], session('locale')) }}'
                            );
                        },
                        success: function(data) {
                            hidePreloader();
                            after_submit();
                            $('#all_accounts').DataTable().ajax.reload();
                            show_notification('success',
                                '{{ trans('messages.delete_success_lang', [], session('locale')) }}'
                            );
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success',
                        '{{ trans('messages.safe_lang', [], session('locale')) }}');
                }
            });
        }


        function edit(id) {
            showPreloader();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                dataType: 'JSON',
                url: "{{ url('edit_account') }}",
                method: "POST",
                data: {
                    id: id,
                    _token: csrfToken
                },
                success: function(fetch) {
                    hidePreloader();
                    after_submit();
                    if (fetch != "") {
                    $(".account_name").val(fetch.account_name);
                    $(".account_branch").val(fetch.account_branch);
                    $(".account_no").val(fetch.account_no);
                    $(".opening_balance").val(fetch.opening_balance);
                    $(".commission").val(fetch.commission);
                    $(".account_type").val(fetch.account_type);
                    $('.default-select').selectpicker('refresh');

                    $(".branch_id").val(fetch.branch_id).trigger('change');
                    $('.default-select').selectpicker('refresh');


                    if(fetch.account_status==1)
                    {
                        $('.account_status').prop('checked',true);
                    }
                    else
                    {
                        $('.account_status').prop('checked',false);
                    }
                    $(".notes").val(fetch.notes);
                    $(".account_id").val(fetch.account_id);

                        $(".modal-title").html('{{ trans('messages.update_lang', [], session('locale')) }}');
                    }

                },
                error: function(html) {
                    hidePreloader();
                    after_submit();
                    show_notification('error',
                        '{{ trans('messages.edit_failed_lang', [], session('locale')) }}');
                    return false;
                }
            });
        }
</script>
