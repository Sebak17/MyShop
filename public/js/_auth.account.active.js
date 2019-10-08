window.onload = function () {
    stickFooter();

    $("#btnActive").click(function () {
        emailActivation();
    });

    $('#inp_email').keyup(function (event) {
        if (event.keyCode === 13)
            emailActivation();
    });
}

grecaptcha.ready(function () {
    generateRecaptchaToken('login');
});

function emailActivation() {
    if (isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS);
        return;
    }

    let email = $("#inp_email").val();

    if (!validateEmail(email)) {
        showAlert(AlertType.ERROR, Lang.EMAIL_INCORRECT);
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
        url: "/system/activateAccountMail",
        method: "POST",
        data: {
            email: email,
            grecaptcha: verifyToken
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    $("#inp_email").val("");
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