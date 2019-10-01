window.onload = function() {
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

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function emailActivation() {
	let email = $("#inp_email").val();

    if (!validateEmail(email)) {
        showAlert(AlertType.ERROR, 'Błędny adres email!');
        return;
    }

    showAlert(AlertType.LOADING, 'Trwa sprawdzanie danych...');

    $.ajax({
        url: "/system/activateAccountMail",
        method: "POST",
        data: {
            email: email,
			grecaptcha: verifyToken
        },
        success: function(data) {
            try {
                if (data.success == true) {
                	$("#inp_email").val("");
                    showAlert(AlertType.SUCCESS, 'Jeśli email jest prawidłowy, wiadomość została wysłana!');
                } else {
                	 generateRecaptchaToken('login');
                	
                    showAlert(AlertType.ERROR, data.msg);
                }

            } catch (e) {
                showAlert(AlertType.ERROR, 'Wystąpił błąd podczas wysyłania!');
            }
        },
        error: function() {
            showAlert(AlertType.ERROR, 'Wystąpił błąd podczas wysyłania!');
        }
    });
}