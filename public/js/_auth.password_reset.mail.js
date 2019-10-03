window.onload = function () {
    stickFooter();

    $("#btnResetPass").click(function () {
        emailResetPassword();
    });

    $('#inp_email').keyup(function (event) {
        if (event.keyCode === 13)
            emailResetPassword();
    });

    $('#inp_phone').keyup(function (event) {
        if (event.keyCode === 13)
            emailResetPassword();
    });
};

grecaptcha.ready(function () {
    generateRecaptchaToken('login');
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function emailResetPassword() {
    let email = $("#inp_email").val();
    let phone = $("#inp_phone").val();

    if (!validateEmail(email)) {
        showAlert(AlertType.ERROR, 'Błędny adres email!');
        return;
    }

    if (!validatePhone(phone)) {
        showAlert(AlertType.ERROR, 'Błędny numer telefonu!');
        return;
    }

    showAlert(AlertType.LOADING, 'Trwa wysyłanie...');

    $.ajax({
        url: "/system/resetPasswordMail",
        method: "POST",
        data: {
            email: email,
            phone: phone,
            grecaptcha: verifyToken
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, 'Jeśli email jest prawidłowy, wiadomość została wysłana!');
                } else {
                    generateRecaptchaToken('login');
                    showAlert(AlertType.ERROR, data.msg);
                }
            } catch (e) {
                showAlert(AlertType.ERROR, 'Wystąpił błąd podczas wysyłania!');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, 'Wystąpił błąd podczas wysyłania!');
        }
    });
}