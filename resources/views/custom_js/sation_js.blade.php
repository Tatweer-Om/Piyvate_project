<script>

$(document).ready(function() {
    // Reset the form when the modal is hidden
    $('#add_session_modal').on('hidden.bs.modal', function() {
        $(".add_session")[0].reset();
        $('.session_id').val('');
        $('#govt_select_div').hide();
        $('#session_name_div').show();
        $('input[name="session_type"][value="normal"]').prop("checked", true);
    });

    // Initialize DataTable
    $('#all_sessions').DataTable({
        "sAjaxSource": "{{ url('show_sation') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
    });

    // Show/Hide Government Select Box based on Session Type
    $('input[name="session_type"]').change(function() {
        if ($(this).val() === 'ministry') {
            $('#govt_select_div').show();
            $('#session_name_div').hide();
        } else {
            $('#govt_select_div').hide();
            $('#session_name_div').show();
        }
    });

    $('.add_session').submit(function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        formData.append('_token', '{{ csrf_token() }}');
        var sessionType = $('input[name="session_type"]:checked').val();
        var sessionName = $('.session_name').val();
        var government = $('#government').val();
        var sessionPrice = $('.session_price').val();
        var id = $('.session_id').val();

        // Validation
        if (sessionType === "ministry" && government === "") {
            show_notification('error', '{{ trans('messages.select_government_lang', [], session('locale')) }}');
            return false;
        }
        if (sessionType === "normal" && sessionName === "") {
            show_notification('error', '{{ trans('messages.enter_session_name_lang', [], session('locale')) }}');
            return false;
        }
        if (sessionPrice === "") {
            show_notification('error', '{{ trans('messages.enter_price_lang', [], session('locale')) }}');
            return false;
        }

        showPreloader();
        before_submit();

        $.ajax({
            type: "POST",
            url: id ? "{{ url('update_sation') }}" : "{{ url('add_sation') }}",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                hidePreloader();
                after_submit();
                show_notification('success', id ?
                    '{{ trans('messages.data_update_success_lang', [], session('locale')) }}' :
                    '{{ trans('messages.data_add_success_lang', [], session('locale')) }}'
                );
                $('#add_session_modal').modal('hide');
                $('#all_sessions').DataTable().ajax.reload();
                if (!id) $(".add_session")[0].reset();
            },
            error: function(data) {
                hidePreloader();
                after_submit();
                show_notification('error', id ?
                    '{{ trans('messages.data_update_failed_lang', [], session('locale')) }}' :
                    '{{ trans('messages.data_add_failed_lang', [], session('locale')) }}'
                );
                $('#all_sessions').DataTable().ajax.reload();
                console.log(data);
            }
        });
    });

});


function edit(id) {
    $('#global-loader').show();
    before_submit();
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        dataType: 'JSON',
        url: "{{ url('edit_sation') }}", // Ensure this route is correctly mapped
        method: "POST",
        data: { id: id, _token: csrfToken },
        success: function(fetch) {
            $('#global-loader').hide();
            after_submit();

            if (fetch) {
                $(".session_type[value='" + fetch.session_type + "']").prop("checked", true);
                $(".session_price").val(fetch.session_price);
                $(".notes").val(fetch.notes);
                $(".session_id").val(fetch.session_id); // Ensure ID is set for update

                if (fetch.session_type === "ministry") {
                    $("#ministry-options").show();
                    $("#ministry2-options").show();

                    $("#session_name_div").hide();
                    $("#government").val(fetch.govt_id);
                    $('.government').selectpicker('refresh');

                    $(".ministry_cat_id").val(fetch.ministry_cat);
                    $('.ministry_cat_id').selectpicker('refresh');


                } else {
                    $("#ministry-options").hide();
                    $("#ministry2-options").hide();

                    $("#session_name_div").show();
                    $(".session_name").val(fetch.session_name);
                }

                $('.default-select').selectpicker('refresh');
                $(".modal-title").html("{{ trans('messages.update_lang', [], session('locale')) }}");
                $('#add_session_modal').modal('show'); // Open the modal
            }
        },
        error: function(html) {
            $('#global-loader').hide();
            after_submit();
            show_notification('error', "{{ trans('messages.edit_failed_lang', [], session('locale')) }}");
            return false;
        }
    });
}



        function del(id) {
            Swal.fire({
                title:  '<?php echo trans('messages.sure_lang',[],session('locale')); ?>',
                text:  '<?php echo trans('messages.delete_lang',[],session('locale')); ?>',
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: '<?php echo trans('messages.delete_it_lang',[],session('locale')); ?>',
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1
            }).then(function (result) {
                if (result.value) {
                    $('#global-loader').show();
                    before_submit();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ url('delete_sation') }}",
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
                            $('#all_sessions').DataTable().ajax.reload();
                            show_notification('success', '<?php echo trans('messages.delete_success_lang',[],session('locale')); ?>');
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    show_notification('success', '<?php echo trans('messages.safe_lang',[],session('locale')); ?>');
                }
            });
        }


document.addEventListener("DOMContentLoaded", function () {
        // Select elements
        const normalRadio = document.getElementById("normal");
        const ministryRadio = document.getElementById("ministry");
        const ministryOptions = document.querySelectorAll(".ministry-options");
        const sessionNameDiv = document.getElementById("session_name_div");

        function toggleFields() {
            if (ministryRadio.checked) {
                ministryOptions.forEach(el => el.style.display = "block");
                sessionNameDiv.style.display = "none"; // Hide session name
            } else {
                ministryOptions.forEach(el => el.style.display = "none");
                sessionNameDiv.style.display = "block"; // Show session name
            }
        }

        // Initially hide ministry fields
        toggleFields();

        // Add event listeners
        normalRadio.addEventListener("change", toggleFields);
        ministryRadio.addEventListener("change", toggleFields);
    });
</script>
