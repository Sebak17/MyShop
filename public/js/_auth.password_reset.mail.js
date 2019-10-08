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

function emailResetPassword() {
    if (isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS);
        return;
    }

    let email = $("#inp_email").val();
    let phone = $("#inp_phone").val();

    if (!validateEmail(email)) {
        showAlert(AlertType.ERROR, Lang.EMAIL_INCORRECT);
        return;
    }

    if (!validatePhone(phone)) {
        showAlert(AlertType.ERROR, Lang.PHONENUMBER_INCORRECT);
        return;
    }

    if (!validateRecaptcha()) {
        showAlert(AlertType.ERROR, Lang.RECATPCHA_VERIFYING);
        if (!isVerifying)
            generateRecaptchaToken('login');
        return;
    }

    isRequest = true;
    showAlert(AlertType.LOADING, Lang.FORM_SENDING);

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
                    $("#inp_phone").val("");
                    showAlert(AlertType.SUCCESS, Lang.REQUEST_EMAIL_SENT);
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
        complete: function () {
            verifyToken = "";
            isRequest = false;
        }
    });
}