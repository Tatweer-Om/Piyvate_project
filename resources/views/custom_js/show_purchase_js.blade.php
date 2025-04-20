<script>
            $('#all_purchase').DataTable({
                "sAjaxSource": "{{ url('show_purchase') }}",
                "bFilter": true,
                'pagingType': 'numbers',
                "ordering": true,
            });



$(document).ready(function() {
    // Handle form submission
    $('.add_purchase_payment').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    // Get the CSRF token for AJAX request
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Collect form data
    var formData = new FormData(this); // This will collect all the data from the form, including files

    $.ajax({
        url: "{{ route('add_purchase_payment') }}", // The URL to send the request to
        method: "POST",
        data: formData,
        processData: false,  // Prevent jQuery from automatically converting the data into a query string
        contentType: false,  // Let the browser set the content-type, since we're uploading files
        success: function(response) {
            // Check if the status is 1
            if(response.status === 1) {
                // Show success notification
                show_notification('success', response.message);

                // Optionally, close the modal or reset the form
                $('#add_purchase_payment_modal').modal('hide');
                $('.add_purchase_payment')[0].reset(); // Reset the form
                $('#all_purchase').DataTable().ajax.reload();

            } else {
                // If the status is not 1, handle the error (if needed)
                show_notification('error', 'An error occurred. Please try again.');

            }
        },
        error: function(response) {
            // Handle the error response (in case the AJAX request fails)
            show_notification('error', 'An error occurred while adding the payment.');
        }
    });
});

});




function get_purchase_payment(id) {
    $('#global-loader').show();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: "<?php echo url('get_purchase_payment'); ?>",
        method: "POST",
        data: {
            id: id,
            _token: csrfToken
        },
        success: function(data) {
            $('#global-loader').hide();

            // Update fields with the data from the controller response
            $('.total_amount').val(data.total_price);
            $('.supplier_name').val(data.supplier_name);
            $('.purchase_date').val(data.purchase_date);
            $('.remaining_amount').val(data.remaining_price);
            $('.invoice_no').val(data.invoice_no);
            $('.purchase_id').val(data.purchase_id);  // Use data.purchase_id here
            $('#purchase_payment_modal').modal('show');
        },
        error: function(data) {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', '<?php echo trans('messages.purchase_payment_failed_lang', [], session('locale')); ?>');
            console.log(data);
            return false;
        }
    });
}

    // Trigger the hidden file input when the preview image is clicked
    document.getElementById('filePreview').addEventListener('click', function () {
        document.getElementById('fileUpload').click();  // Triggers the hidden file input
    });

    // Handle file input change event to preview the selected file
    document.getElementById('fileUpload').addEventListener('change', function (event) {
        let file = event.target.files[0];
        let preview = document.getElementById('filePreview');
        let fileNameDisplay = document.getElementById('fileName');
        let removeButton = document.getElementById('removeFile');

        if (file) {
            let fileType = file.type;
            let fileName = file.name.toLowerCase();

            // If the file is an image, show the image preview
            if (fileType.startsWith('image')) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result; // Show image preview
                };
                reader.readAsDataURL(file);
            } else {
                // Handle non-image files (PDF, Word, Excel)
                if (fileName.endsWith('.pdf')) {
                    preview.src = "{{ asset('images/dummy_images/pdf.png') }}";
                } else if (fileName.endsWith('.doc') || fileName.endsWith('.docx')) {
                    preview.src = "{{ asset('images/dummy_images/word.jpeg') }}";
                } else if (fileName.endsWith('.xls') || fileName.endsWith('.xlsx')) {
                    preview.src = "{{ asset('images/dummy_images/excel.jpeg') }}";
                } else {
                    preview.src = "{{ asset('images/dummy_images/file.png') }}"; // Default file icon
                }
            }

            // Display the file name
            fileNameDisplay.textContent = file.name;

            // Show the remove button
            removeButton.style.display = 'block';
        }
    });

    // Remove file functionality
    document.getElementById('removeFile').addEventListener('click', function () {
        let preview = document.getElementById('filePreview');
        let fileNameDisplay = document.getElementById('fileName');
        let fileInput = document.getElementById('fileUpload');

        // Reset the file input
        fileInput.value = '';

        // Reset the preview and file name
        preview.src = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
        fileNameDisplay.textContent = '';

        // Hide the remove button
        this.style.display = 'none';
    });

// delete purchase
function del_purchase(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang', [], session('locale')); ?>',
            text: '<?php echo trans('messages.delete_lang', [], session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang', [], session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $('#global-loader').show();
                $.ajax({
                    url: "<?php echo url('delete_purchase'); ?>",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        $('#global-loader').hide();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang', [], session('locale')); ?>');
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        $('#all_purchase').DataTable().ajax.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang', [], session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang', [], session('locale')); ?>');
            }
        });
    }


    function del_payment(id) {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        Swal.fire({
            title: '<?php echo trans('messages.sure_lang', [], session('locale')); ?>',
            text: '<?php echo trans('messages.delete_lang', [], session('locale')); ?>',
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: '<?php echo trans('messages.delete_it_lang', [], session('locale')); ?>',
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function(result) {
            if (result.value) {
                $('#global-loader').show();
                $.ajax({
                    url: "<?php echo url('delete_purchase_payment'); ?>",
                    type: 'POST',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    error: function() {
                        $('#global-loader').hide();
                        show_notification('error', '<?php echo trans('messages.delete_failed_lang', [], session('locale')); ?>');
                    },
                    success: function(data) {
                        $('#global-loader').hide();
                        location.reload();
                        show_notification('success', '<?php echo trans('messages.delete_success_lang', [], session('locale')); ?>');
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                show_notification('success', '<?php echo trans('messages.safe_lang', [], session('locale')); ?>');
            }
        });
    }



</script>
