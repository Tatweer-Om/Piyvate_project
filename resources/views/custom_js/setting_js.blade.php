<script>
    $(document).on('submit', '.add_setting', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
    url: '{{ route("add_setting") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        if (response.success) {
            show_notification('success', '<?php echo trans('messages.data_saved_success',[],session('locale')); ?>');
            // location.reload();

        } else {
            show_notification('error', '<?php echo trans('messages.data_not_saved',[],session('locale')); ?>');
        }
    },
    error: function(xhr) {
        show_notification('error', '<?php echo trans('messages.some_error_occured',[],session('locale')); ?>');
    }
});

});

$(document).on('submit', '.add_fee', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
    url: '{{ route("appointment_fee") }}',
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        if (response.success) {
            show_notification('success', '<?php echo trans('messages.data_saved_success',[],session('locale')); ?>');
            // location.reload();

        } else {
            show_notification('error', '<?php echo trans('messages.data_not_saved',[],session('locale')); ?>');
        }
    },
    error: function(xhr) {
        show_notification('error', '<?php echo trans('messages.some_error_occured',[],session('locale')); ?>');
    }
});



});


document.getElementById('imagePreview').addEventListener('click', function() {
            document.getElementById('imageUpload').click();
        });

        document.getElementById('imageUpload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('removeImage').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('removeImage').addEventListener('click', function() {
            document.getElementById('imagePreview').src = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
            document.getElementById('imageUpload').value = "";
            this.style.display = 'none';
        });

</script>
