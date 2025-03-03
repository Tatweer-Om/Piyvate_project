<script>

$(document).ready(function () {
        $(".logout_button").on("click", function (e) {
            e.preventDefault(); // Prevent default link action

            $.ajax({
                url: "{{ url('logout') }}",
                type: "get",
                data: { _token: "{{ csrf_token() }}" },
                dataType: "json",
                success: function (response) {
                    if (response.status === 1) {
                        show_notification('success', "{{ trans('messages.logout_success') }}");
                        window.location.href = "{{ url('login_page') }}"; // Redirect to login page

                    } else {
                        show_notification('error', "{{ trans('messages.logout_failed') }}");
                    }
                },
                error: function () {
                    show_notification('error', "{{ trans('messages.something_went_wrong') }}");
                }
            });
        });
    });

// document.getElementById('imagePreview').addEventListener('click', function() {
//     document.getElementById('imageUpload').click();
// });

// document.getElementById('imageUpload').addEventListener('change', function(event) {
//     let file = event.target.files[0];
//     if (file) {
//         let reader = new FileReader();
//         reader.onload = function(e) {
//             document.getElementById('imagePreview').src = e.target.result;
//             document.getElementById('removeImage').style.display = "block";
//         };
//         reader.readAsDataURL(file);
//     }
// });

// document.getElementById('removeImage').addEventListener('click', function() {
//     document.getElementById('imagePreview').src = "{{ asset('images/dummy_images/cover-image-icon.png') }}";
//     document.getElementById('imageUpload').value = "";
//     this.style.display = "none";
// });



function show_notification(type, msg) {
        toastr.options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: 'toast-top-right', // Set position to top-right
            preventDuplicates: false,
            onclick: null,
            showDuration: '300',
            hideDuration: '1000',
            timeOut: '5000',
            extendedTimeOut: '1000',
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };
        if (type == "success") {
            toastr.success(msg, type);
        } else if (type == "error") {
            toastr.error(msg, type);
        } else if (type == "warning") {
            toastr.warning(msg, type);
        }
    }

    function before_submit() {
        $('.submit_form').attr('disabled', true);
        $('.submit_form').html(
            'Please wait <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>');
    }

    function after_submit() {
        $('.submit_form').attr('disabled', false);
        $('.submit_form').html('Submit');
    }

    function showPreloader() {
    if ($('#preloader').length === 0) {
        $('body').append(`
            <div id="preloader">
                <div class="sk-three-bounce">
                    <div class="sk-child sk-bounce1"></div>
                    <div class="sk-child sk-bounce2"></div>
                    <div class="sk-child sk-bounce3"></div>
                </div>
            </div>
        `);
    }
    $('#preloader').show();
}

function hidePreloader() {
    $('#preloader').remove(); // Completely removes it after hiding
}







</script>
