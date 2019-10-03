$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

grecaptcha.ready(function () {
    generateRecaptchaToken('login');
});

window.onload = function () {
    stickFooter();

    $("#btnChange").click(function () {
        formResetPassword();
    });

    $('#inp_password2').keyup(function (event) {
        if (event.keyCode === 13)
            formResetPassword();
    });
};

function formResetPassword() {
    if(isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS);
        return;
    }

    let pass1 = $("#inp_password1").val();
    let pass2 = $("#inp_password2").val();

    if (!validatePassword(pass1)) {
        showAlert(AlertType.ERROR, Lang.PASSWORD_NOT_IN_RANGE);
        return;
    }

    if (pass1 !== pass2) {
        showAlert(AlertType.ERROR, Lang.PASSWORDS_NOT_MATCH);
        return;
    }

    if(!validateRecaptcha()) {
        showAlert(AlertType.ERROR, Lang.RECATPCHA_VERIFYING);
        if(!isVerifying)
            generateRecaptchaToken('login');
        return;
    }

    isRequest = true;
    showAlert(AlertType.LOADING, Lang.FORM_SENDING);

    $.ajax({
        url: "/system/resetPasswordChange",
        method: "POST",
        data: {
            password: pass1,
            grecaptcha: verifyToken
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, 'Hasło zostało zmienione!');
                } else {
                    generateRecaptchaToken('login');
                    showAlert(AlertType.ERROR, data.msg);
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR);
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR);
        },
        complete: function() {
            verifyToken = "";
            isRequest = false;
        }
    });
}