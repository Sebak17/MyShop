window.onload = function () {
    loadAuthSignIn();
};

grecaptcha.ready(function () {
    generateRecaptchaToken('login');
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
    if (isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS);
        return;
    }

    let d_email = $("#inp_email").val();
    let d_password = $("#inp_password").val();

    if (!validateEmail(d_email)) {
        showAlert(AlertType.ERROR, Lang.EMAIL_INCORRECT);
        return;
    }

    if (!validatePassword(d_password)) {
        showAlert(AlertType.ERROR, Lang.PASSWORD_NOT_IN_RANGE);
        return;
    }

    if(!validateRecaptcha()) {
        showAlert(AlertType.ERROR, Lang.RECATPCHA_VERIFYING);
        if(!isVerifying)
            generateRecaptchaToken('login');
        return;
    }

    isRequest = true;
    showAlert(AlertType.LOADING, Lang.LOGIN_IN_PROGRESS);

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
                    showAlert(AlertType.SUCCESS, Lang.LOGIN_SUCCESS);
                    window.location.href = "/panel";
                } else {
                    $("#inp_password").val("");
                    generateRecaptchaToken('login');
                    showAlert(AlertType.ERROR, data.msg);
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.LOGIN_ERROR);
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.LOGIN_ERROR);
        },
        complete: function() {
            verifyToken = "";
            isRequest = false;
        }
    });
}