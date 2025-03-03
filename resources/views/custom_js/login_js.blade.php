<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
    $(".login_form").on("submit", function (e) {
        e.preventDefault(); // منع إعادة تحميل الصفحة

        $.ajax({
            url: "{{ url('login') }}",
            type: "POST",
            data: $(this).serialize(), // إرسال البيانات إلى الكنترولر
            dataType: "json",
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = "{{ url('/') }}"; // إعادة توجيه إلى الصفحة الرئيسية
                    show_notification('success', response.message);

                } else {
                    show_notification('error', response.message);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                show_notification('error', response.message);
                if (errors) {
                    errorMessage = Object.values(errors).join("\n");
                }
                alert(errorMessage);
            },
        });
    });
});

</script>
