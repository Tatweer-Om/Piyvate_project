<script>
    $(document).ready(function() {
        $('#add_expense_modal').on('hidden.bs.modal', function() {
            $(".add_expense")[0].reset();
            $('.expense_id').val('');
        });

        $('#all_expenses').DataTable({
            "sAjaxSource": "{{ url('show_expense') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

        $('.add_expense').submit(function(e) {

            e.preventDefault();

            var formdatas = new FormData($(this)[0]);
            formdatas.append('_token', '{{ csrf_token() }}');
            var title = $('.expense_name').val();
            var id = $('.expense_id').val();

            if (title === "") {
                show_notification('error',
                    '{{ trans('messages.add_expense_name_lang', [], session('locale')) }}');
                return false;
            }

            showPreloader();
            before_submit();

            $.ajax({
                type: "POST",
                url: id ? "{{ url('update_expense') }}" :
                    "{{ url('add_expense') }}",
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
                    $('#add_expense_modal').modal('hide');
                    $('#all_expenses').DataTable().ajax.reload();
                    if (!id) $(".add_expense")[0].reset();
                },
                error: function(data) {
                    hidePreloader();
                    after_submit();
                    show_notification('error', id ?
                        '{{ trans('messages.data_update_failed_lang', [], session('locale')) }}' :
                        '{{ trans('messages.data_add_failed_lang', [], session('locale')) }}'
                    );
                    $('#all_expenses').DataTable().ajax.reload();
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
                        url: "{{ url('delete_expense') }}",
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
                            $('#all_expenses').DataTable().ajax.reload();
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
                url: "{{ url('edit_expense') }}",
                method: "POST",
                data: {
                    id: id,
                    _token: csrfToken
                },
                success: function(fetch) {
                    hidePreloader();
                    after_submit();
                    if (fetch != "") {
                        $(".expense_name").val(fetch.expense_name);
                        $(".category_id").val(fetch.category_id).trigger('change');
                        $('.default-select').selectpicker('refresh');

                        $(".expense_date").val(fetch.expense_date);
                        $(".amount").val(fetch.amount);
                        $(".account_id").val(fetch.payment_method).trigger('change');
                        $('.default-select').selectpicker('refresh');

                        $(".expense_id").val(fetch.expense_id).trigger('change');
                        $(".notes").val(fetch.notes);

                        if (fetch.expense_image) {
                    let fileType = fetch.file_type;

                    if (fileType === 'image') {
                        $('#filePreview').attr('src', fetch.expense_image); // Show image preview
                        $('#filePreview').show();
                    } else if (fileType === 'pdf') {
                        $('#filePreview').attr('src', fetch.expense_image); // Show PDF icon
                        $('#filePreview').show();
                    } else if (fileType === 'word') {
                        $('#filePreview').attr('src', fetch.expense_image); // Show Word icon
                        $('#filePreview').show();
                    } else if (fileType === 'excel') {
                        $('#filePreview').attr('src', fetch.expense_image); // Show Excel icon
                        $('#filePreview').show();
                    } else {
                        $('#filePreview').attr('src', fetch.expense_image); // Show generic file icon
                        $('#filePreview').show();
                    }
                } else {
                    $('#filePreview').hide(); // Hide the preview if no file
                }

                        $(".expense_id").val(fetch.expense_id);

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



// Trigger the file input when the user clicks the image preview
document.getElementById('filePreview').addEventListener('click', function () {
    document.getElementById('fileUpload').click();  // Triggers the hidden file input
});

// Handle file input change event to preview the selected file
document.getElementById('receiptFile').addEventListener('change', function (event) {
        let file = event.target.files[0];
        let preview = document.getElementById('filePreview');
        let fileNameDisplay = document.getElementById('fileName');
        let removeButton = document.getElementById('removeFile');

        if (file) {
            let fileType = file.type;
            let fileName = file.name.toLowerCase();

            if (fileType.startsWith('image')) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result; // Show image preview
                };
                reader.readAsDataURL(file);
            } else {
                // Handle document files
                if (fileName.endsWith('.pdf')) {
                    preview.src = "{{ asset('images/dummy_images/pdf.png') }}";
                } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                    preview.src = "{{ asset('images/dummy_images/word.jpeg') }}";
                } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                    preview.src = "{{ asset('images/dummy_images/excel.jpeg') }}";
                } else {
                    preview.src = "{{ asset('images/dummy_images/file.png') }}";
                }
            }

            // Display file name
            fileNameDisplay.textContent = file.name;

            // Show remove button
            removeButton.style.display = 'block';
        }
    });

    // Remove file functionality
    document.getElementById('removeFile').addEventListener('click', function () {
        let preview = document.getElementById('filePreview');
        let fileNameDisplay = document.getElementById('fileName');
        let fileInput = document.getElementById('receiptFile');

        // Reset the input field
        fileInput.value = '';

        // Reset the preview and file name
        preview.src = "{{ asset('images/dummy_images/file.png') }}";
        fileNameDisplay.textContent = 'Drag and drop a file here';

        // Hide remove button
        this.style.display = 'none';
    });

// Remove the file and reset preview
document.getElementById('removeFile').addEventListener('click', function () {
    document.getElementById('fileUpload').value = ''; // Clear file input
    document.getElementById('filePreview').src = "{{ asset('images/dummy_images/cover-image-icon.png') }}"; // Reset preview
    document.getElementById('fileName').textContent = ''; // Clear file name
    this.style.display = 'none'; // Hide remove button
});






</script>
