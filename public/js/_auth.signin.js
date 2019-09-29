window.onload = function () {
    stickFooter();

    loadAuthSignIn();
};

grecaptcha.ready(function () {
    generateRecaptchaToken('login');
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function loadAuthSignIn() {
    $("#btnLogin").click(function () {
        signIn();
    });

    $('#inp_email').keyup(function (event) {
        if (event.keyCode === 13)
            signIn();
    });

    $('#inp_password').keyup(function (event) {
        if (event.keyCode === 13)
            signIn();
    });
}

function signIn() {
    let d_email = $("#inp_email").val();
    let d_password = $("#inp_password").val();

    if (!validateEmail(d_email)) {
        showAlert(AlertType.ERROR, 'Błędny adres email!');
        return;
    }

    if (d_password.length < 4) {
        showAlert(AlertType.ERROR, 'Błędne hasło!');
        return;
    }

    if (verifyToken.length < 4) {
        showAlert(AlertType.ERROR, 'Błędny token!');
        return;
    }

    showAlert(AlertType.LOADING, 'Trwa logowanie...');

    $.ajax({
        url: "/system/signIn",
        method: "POST",
        data: {
            email: d_email,
            password: d_password,
            grecaptcha: verifyToken
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, 'Zalogowano pomyślnie!');
                    window.location.href = "/panel";
                } else {
                    $("#password").val("");
                    generateRecaptchaToken('login');
                    showAlert(AlertType.ERROR, data.msg);
                }
            } catch (e) {
                showAlert(AlertType.ERROR, 'Wystąpił błąd podczas logowania!');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, 'Wystąpił błąd podczas logowania!');
        }
    });
}