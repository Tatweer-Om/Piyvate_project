<script>
    $(document).ready(function() {
        // Reset the form when the modal is hidden
        $('#add_department_modal').on('hidden.bs.modal', function() {
            $(".add_department")[0].reset();
            $('.department_id').val('');
        });

        // Initialize DataTable
        $('#all_department').DataTable({
            "sAjaxSource": "{{ url('show_department') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_department').submit(function(e) {
            e.preventDefault();

            var formdatas = new FormData($(this)[0]);
            formdatas.append('_token', '{{ csrf_token() }}');
            var title = $('.department_name').val();
            var id = $('.department_id').val();

            // Validation
            if (title === "") {
                show_notification('error',
                    '{{ trans('messages.add_department_name_lang', [], session('locale')) }}');
                return false;
            }

            showPreloader();
            before_submit();

            $.ajax({
                type: "POST",
                url: id ? "{{ url('update_department') }}" :
                    "{{ url('add_department') }}",
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
                    $('#add_department_modal').modal('hide');
                    $('#all_department').DataTable().ajax.reload();
                    if (!id) $(".add_department")[0].reset();
                },
                error: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error', id ?
                        '{{ trans('messages.data_update_failed_lang', [], session('locale')) }}' :
                        '{{ trans('messages.data_add_failed_lang', [], session('locale')) }}'
                    );
                    $('#all_department').DataTable().ajax.reload();
                    console.log(data);
                }
            });
        });

        // Edit function


        // Delete function

    });

    function edit(id) {
            showPreloader();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                dataType: 'JSON',
                url: "{{ url('edit_department') }}",
                method: "POST",
                data: {
                    id: id,
                    _token: csrfToken
                },
                success: function(fetch) {
                    hidePreloader();
                    after_submit();
                    if (fetch != "") {
                        $(".department_name").val(fetch.department_name);
                        $(".department_id").val(fetch.department_id);
                        $(".modal-title").html(
                            '{{ trans('messages.update_lang', [], session('locale')) }}');
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
                        url: "{{ url('delete_department') }}",
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
                            $('#all_department').DataTable().ajax.reload();
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
</script>
