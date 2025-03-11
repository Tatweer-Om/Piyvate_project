
<script>

$(document).ready(function() {
    $('#appointmentModal').on('hidden.bs.modal', function() {
        $(".appointment_form")[0].reset();
        $('.appointment_id').val('');
    });

    $('#appointment_table').DataTable({
        "sAjaxSource": "{{ url('show_appointments') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
        "order": [[6, "desc"]]
    });

    $('#appointmentModal').off().on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData($('.appointment_form')[0]);
        formData.append('_token', '{{ csrf_token() }}');
        var patientName = $('#first_name').val();
        var appointmentDate = $('#appointment_date').val();

        var id = $('.appointment_id').val();

        if (patientName === "") {
            show_notification('error', 'Please enter the patient’s name.');
            return false;
        }
        if (appointmentDate === "") {
            show_notification('error', 'Please select an appointment date.');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_appointment') }}" : "{{ url('add_appointment') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ? 'Appointment updated successfully' : 'Appointment added successfully');
                $('#appointmentModal').modal('hide');
                $('#appointment_table').DataTable().ajax.reload();
                if (!id) $(".appointment_form")[0].reset();
            },
            error: function(data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ? 'Failed to update appointment' : 'Failed to add appointment');
                $('#appointment_table').DataTable().ajax.reload();
            }
        });
    });
});

function editAppointment(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_appointment') }}",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(data) {
            $('#global-loader').hide();
            after_submit();
            if (data) {
                $("#title").val(data.title);
                $("#first_name").val(data.first_name);
                $("#second_name").val(data.second_name);
                $("#country").val(data.country_id);
                $("#service").val(data.service_id);
                $("#doctor").val(data.doctor_id);
                $("#mobile").val(data.mobile);
                $("#id_passport").val(data.id_passport);
                $("#appointment_date").val(data.appointment_date);
                $("#time_from").val(data.time_from);
                $("#time_to").val(data.time_to);
                $("#notes").val(data.notes);
                $('.appointment_id').val(data.id);
                $('#appointmentModal').modal('show');
            }
        },
        error: function() {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', 'Failed to fetch appointment data.');
        }
    });
}

function deleteAppointment(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This appointment will be permanently deleted.',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ url('delete_appointment') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                success: function() {
                    $('#global-loader').hide();
                    after_submit();
                    $('#appointment_table').DataTable().ajax.reload();
                    show_notification('success', 'Appointment deleted successfully');
                },
                error: function() {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error', 'Failed to delete appointment.');
                }
            });
        }
    });
}
</script>
