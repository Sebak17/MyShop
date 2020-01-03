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

    $('#inp_login').keyup(function (event) {
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

    let d_login = $("#inp_login").val();
    let d_password = $("#inp_password").val();

    if (!validateLogin(d_login)) {
        showAlert(AlertType.ERROR, Lang.LOGIN_INCORRECT);
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
        url: "/systemAdmin/signIn",
        method: "POST",
        data: {
            login: d_login,
            password: d_password,
            grecaptcha: verifyToken
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.LOGIN_SUCCESS);
                    window.location.href = "/admin/panel";
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