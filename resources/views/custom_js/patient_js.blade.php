<script>
 $(document).ready(function() {
    $('#add_patient').on('hidden.bs.modal', function() {
            $(".add_patient")[0].reset();
            $('.patient_id').val('');
        });


        $('#all_patient').DataTable({
            "sAjaxSource": "{{ url('show_patient') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [[6, "desc"]]
        });


    });

    $('.add_patient').submit(function(e) {
        e.preventDefault();

        var formData = new FormData($('.add_patient')[0]);
        formData.append('_token', '{{ csrf_token() }}');
        var firstName = $('#first_name').val();
        var mobile = $('#mobile').val();
        var id = $('.patient_id').val();

        if (firstName === "") {
            show_notification('error', '<?php echo trans('messages.add_patient_name_lang',[],session('locale')); ?>');
            return false;
        }
        if (mobile === "") {
            show_notification('error', '<?php echo trans('messages.provide_mobile_lang',[],session('locale')); ?>');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_patient') }}" : "{{ url('add_patient') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '<?php echo trans('messages.data_update_success_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_success_lang',[],session('locale')); ?>'
                );
                $('#add_patient').modal('hide');
                $('#all_patient').DataTable().ajax.reload();
                if (!id) $(".add_patient")[0].reset();
            },
            error: function(response) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '<?php echo trans('messages.data_update_failed_lang',[],session('locale')); ?>' :
                    '<?php echo trans('messages.data_add_failed_lang',[],session('locale')); ?>'
                );
                $('#all_patient').DataTable().ajax.reload();
            }
        });
    });

function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_patient') }}",
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(patient) {
            $('#global-loader').hide();
            after_submit();
            if (patient != "") {
                $(".patient_id").val(patient.patient_id);
                $("#title").val(patient.title);
                $('#title').selectpicker('refresh');
                $("#first_name").val(patient.first_name);
                $("#second_name").val(patient.second_name);
                $("#mobile").val(patient.mobile);
                $("#gender").val(patient.gender);
                $("#age_input").val(patient.age);
                $("#age_value").text(patient.age);
                $("#age_badge").show();
                $("#gender_value").text(patient.gender);
                $(".gender").val(patient.gender);
                $("#gender_badge").show();
                $("#id_passport").val(patient.id_passport);
                $("#dob").val(patient.dob);
                $("#country").val(patient.country);
                $(".country").selectpicker('refresh');

                $("#details").val(patient.details);
                $(".modal-title").html('<?php echo trans('messages.update_lang',[],session('locale')); ?>');
            }
        },
        error: function() {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', '<?php echo trans('messages.edit_failed_lang',[],session('locale')); ?>');
            return false;
        }
    });
}

function del(id) {
    Swal.fire({
        title: '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
        text: '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
        confirmButtonClass: "btn btn-primary",
        cancelButtonClass: "btn btn-danger ml-1",
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            $('#global-loader').show();
            before_submit();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "{{ url('delete_patient') }}",
                type: 'POST',
                data: { id: id, _token: csrfToken },
                error: function() {
                    $('#global-loader').hide();
                    after_submit();
                    show_notification('error', '<?php echo trans('messages.delete_failed_lang',[],session('locale')); ?>');
                },
                success: function() {
                    $('#global-loader').hide();
                    after_submit();
                    $('#all_patient').DataTable().ajax.reload();
                    show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
        }
    });
}

$(document).ready(function () {
    // Calculate and display age in years and months when DOB is selected
    $("#dob").on("change", function () {
        let dob = new Date($(this).val());
        let today = new Date();
        let ageYears = today.getFullYear() - dob.getFullYear();
        let ageMonths = today.getMonth() - dob.getMonth();

        // Adjust if the birthday hasn't occurred yet this year
        if (today.getDate() < dob.getDate()) {
            ageMonths--;
        }

        if (ageMonths < 0) {
            ageYears--;
            ageMonths += 12;
        }

        if (!isNaN(ageYears) && ageYears >= 0) {
            let ageText = `${ageYears} years`;
            if (ageMonths > 0) {
                ageText += ` ${ageMonths} months`;
            }

            $("#age_value").text(ageText);
            $("#age_input").val(`${ageYears} years ${ageMonths} months`);
            $("#age_badge").show();
        } else {
            $("#age_badge").hide();
        }
    });

    // Update gender based on selected title
    $("#title").on("change", function () {
        let title = $(this).val();
        let gender = "";

        if (title === "1") gender = "Female";  // Miss
        if (title === "2") gender = "Male";    // Mr.
        if (title === "3") gender = "Female";  // Mrs.

        if (gender) {
            $("#gender_value").text(gender);
            $("#gender_input").val(gender);
            $("#gender_badge").show();
        } else {
            $("#gender_badge").hide();
        }
    });
});

</script>
