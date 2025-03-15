
<script>

$(document).ready(function() {
    $('#all_appointments').DataTable({
        "sAjaxSource": "{{ url('show_appointment') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
    });

    $('.add_appointment').submit(function(e) {
        e.preventDefault();

        var formdatas = new FormData($(this)[0]);
        formdatas.append('_token', '{{ csrf_token() }}');

        var title = $('#title').val(); 
        if (title === "") {
            show_notification('error', '{{ trans('messages.add_patient_name_lang', [], session('locale')) }}');
            return false;
        }
         var title = $('#doctor').val(); 
        if (title === "") {
            show_notification('error', '{{ trans('messages.add_doctor_name_lang', [], session('locale')) }}');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: "{{ url('add_appointment') }}",
            data: formdatas,
            contentType: false,
            processData: false,
            success: function(response) {
                hidePreloader();
                after_submit();
                show_notification('success', '{{ trans('messages.appointment_add_success_lang', [], session('locale')) }}');
                $('.add_appointment')[0].reset();
                $('#all_appointments').DataTable().ajax.reload();
            },
            error: function(response) {
                hidePreloader();
                after_submit();
                show_notification('error', '{{ trans('messages.appointment_add_failed_lang', [], session('locale')) }}');
                console.log(response);
            }
        });
    });
});

function deleteAppointment(id) {
    Swal.fire({
        title: '{{ trans('messages.sure_lang', [], session('locale')) }}',
        text: '{{ trans('messages.delete_lang', [], session('locale')) }}',
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: '{{ trans('messages.delete_it_lang', [], session('locale')) }}',
    }).then(function(result) {
        if (result.value) {
            showPreloader();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ url('delete_appointment') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                error: function() {
                    hidePreloader();
                    after_submit();
                    show_notification('error', '{{ trans('messages.delete_failed_lang', [], session('locale')) }}');
                },
                success: function() {
                    hidePreloader();
                    after_submit();
                    $('#all_appointments').DataTable().ajax.reload();
                    show_notification('success', '{{ trans('messages.delete_success_lang', [], session('locale')) }}');
                }
            });
        }
    });
}

</script>
