var registerProgress = 0,
    registerValues = [];


// STEP 1 - EMAIL | PASSWORD
// STEP 2 - PERSONAL INFO
// STEP 3 - LOCATION INFO

window.onload = function () {

    stickFooter();

    loadAuthSignUp();
    startProccess();

};

grecaptcha.ready(function () {
    generateRecaptchaToken('login');
});

function loadAuthSignUp() {

    $(document).on('keypress', function (e) {
        if (e.which == 13)
            registerStepNext();
    });

    $("#btnRegNext").click(function () {
        registerStepNext();
    });

    $("#btnRegBack").click(function () {
        registerStepBack();
    });

}

function startProccess() {
    registerRender_1();

    registerProgress = 1;
}

function registerStepNext() {
    switch (registerProgress) {
        case 1:
            registerProccess_1();
            break;
        case 2:
            registerProccess_2();
            break;
        case 3:
            registerProccess_3();
            break;
        case 4:
            registerProccess_end();
            break;
    }
}

function registerStepBack() {
    $("#btnRegNext").removeAttr("disabled");
    switch (registerProgress) {
        case 2:
            registerRender_1();
            registerProgress = 1;
            break;
        case 3:
            registerRender_2();
            registerProgress = 2;
            break;
        case 4:
            registerRender_3();
            registerProgress = 3;
            break;

    }
}



function registerProccess_1() {

    let email = $("#inp_email").val();
    let pass1 = $("#inp_password1").val();
    let pass2 = $("#inp_password2").val();

    if (!validateEmail(email)) {
        showAlert(AlertType.ERROR, Lang.EMAIL_INCORRECT);
        return;
    }

    if (!validatePassword(pass1)) {
        showAlert(AlertType.ERROR, Lang.PASSWORD_NOT_IN_RANGE);
        return;
    }

    if (pass1 !== pass2) {
        showAlert(AlertType.ERROR, Lang.PASSWORDS_NOT_MATCH);
        return;
    }

    registerValues['email'] = email;
    registerValues['pass'] = pass1;

    registerProgress = 2;
    registerRender_2();
}


function registerProccess_2() {

    let fname = $("#inp_firstname").val();
    let sname = $("#inp_surname").val();
    let phone = $("#inp_phone").val();

    fname = fname.replace(/\s+/g, "");
    sname = sname.replace(/\s+/g, "");
    phone = phone.replace(/\s+/g, "");

    if (!validatePersonalNameLength(fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_NOT_IN_RANGE);
        return;
    }

    if (!validatePersonalNameString(fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_ONLY_LETTERS);
        return;
    }

    if (!validatePersonalNameLength(sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_NOT_IN_RANGE);
        return;
    }

    if (!validatePersonalNameString(sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_ONLY_LETTERS);
        return;
    }

    if (!validatePhone(phone)) {
        showAlert(AlertType.ERROR, Lang.PHONENUMBER_INCORRECT);
        return;
    }

    registerValues['firstname'] = fname;
    registerValues['surname'] = sname;
    registerValues['phone'] = phone;

    registerProgress = 3;
    registerRender_3();

}

function registerProccess_3() {

    let district = $("#inp_district").val();
    let city = $("#inp_city").val();
    let zipcode = $("#inp_zipcode").val();
    let address = $("#inp_address").val();

    zipcode = zipcode.replace(/\s+/g, "");

    if (!validateDistrict(district)) {
        showAlert(AlertType.ERROR, Lang.DISTRICT_INCORRECT);
        return;
    }

    if (!validateCityLength(city)) {
        showAlert(AlertType.ERROR, Lang.CITY_NOT_IN_RANGE);
        return;
    }

    if (!validateCityString(city)) {
        showAlert(AlertType.ERROR, Lang.CITY_NAME_INCORRECT);
        return;
    }

    if (!validateZipCode(zipcode)) {
        showAlert(AlertType.ERROR, Lang.ZIPCODE_INCORRECT);
        return;
    }

    if (!validateAdressLength(address)) {
        showAlert(AlertType.ERROR, Lang.ADDRESS_NOT_IN_RANGE);
        return;
    }

    if (!validateAdressString(address)) {
        showAlert(AlertType.ERROR, Lang.ADDRESS_NAME_INCORRECT);
        return;
    }


    registerValues['district'] = district;
    registerValues['city'] = city;
    registerValues['zipcode'] = zipcode;
    registerValues['address'] = address;



    registerProgress = 4;
    registerRender_4();

    systemSignUp();

}


function registerRender_1() {
    let data = String.raw `
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-at"></i></span>
            </div>
            <input id="inp_email" type="email" placeholder="Podaj email" class="form-control" autofocus>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-key"></i></span>
            </div>
            <input id="inp_password1" type="password" placeholder="Podaj hasło" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-key"></i></span>
            </div>
            <input id="inp_password2" type="password" placeholder="Powtórz hasło" class="form-control">
        </div>
    </div>`;

    $("#btnRegBack").addClass('d-none');
    $("#registerBox").html(data);

    showAlert(AlertType.NONE);
    changeProgress(0);

    if (typeof registerValues['email'] != "undefined" && registerValues['email'] != "")
        $("#inp_email").val(registerValues['email']);
}


function registerRender_2() {
    let data = String.raw `
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-id-card"></i></span>
            </div>
            <input id="inp_firstname" type="text" placeholder="Podaj swoje imię" class="form-control" autofocus>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-2x fa-id-card"></i></span>
            </div>
            <input id="inp_surname" type="text" placeholder="Podaj swoje nazwisko" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-mobile-alt"></i>&nbsp; +48</span>
            </div>
            <input id="inp_phone" type="text" placeholder="Podaj swój numer telefonu" class="form-control" maxlength="9">
        </div>
    </div>`;

    $("#btnRegBack").removeClass('d-none');
    $("#registerBox").html(data);

    $('#inp_phone').maskPhone();

    showAlert(AlertType.NONE);
    changeProgress(33);
    changeNextButton();

    if (typeof registerValues['firstname'] != "undefined" && registerValues['firstname'] != "")
        $("#inp_firstname").val(registerValues['firstname']);

    if (typeof registerValues['surname'] != "undefined" && registerValues['surname'] != "")
        $("#inp_surname").val(registerValues['surname']);

    if (typeof registerValues['phone'] != "undefined" && registerValues['phone'] != "")
        $("#inp_phone").val(registerValues['phone'].replace(/(.{3})/g,"$1 "));
}

function registerRender_3() {

    let data = String.raw `
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-map-marker"></i></span>
            </div>
            <select class="form-control" id="inp_district" autofocus>
                <option value="1">Dolnośląskie</option>
                <option value="2">Kujawsko-Pomorskie</option>
                <option value="3">Lubelskie</option>
                <option value="4">Lubuskie</option>
                <option value="5">Łódzkie</option>
                <option value="6">Małopolskie</option>
                <option value="7">Mazowieckie</option>
                <option value="8">Opolskie</option>
                <option value="9">Podkarpackie</option>
                <option value="10">Podlaskie</option>
                <option value="11">Pomorskie</option>
                <option value="12">Śląskie</option>
                <option value="13">Świętokrzyskie</option>
                <option value="14">Warmińsko-Mazurskie</option>
                <option value="15">Wielkopolskie</option>
                <option value="16">Zachodniopomorskie</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-building"></i></span>
            </div>
            <input id="inp_city" type="text" placeholder="Podaj swoje miasto" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-building"></i></span>
            </div>
            <input id="inp_zipcode" type="text" placeholder="Podaj kod pocztowy miasta" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-2x fa-address-card"></i></span>
            </div>
            <input id="inp_address" type="text" placeholder="Podaj swój adres zamieszkania" class="form-control">
        </div>
    </div>`;

    $("#btnRegBack").removeClass('d-none');
    $("#registerBox").html(data);

    changeProgress(66);
    showAlert(AlertType.NONE);
    changeNextButton(String.raw `Zarejestruj <i class="fas fa-arrow-up"></i>`);

    if (typeof registerValues['district'] != "undefined" && registerValues['district'] != "")
        $("#inp_district").val(registerValues['district']);

    if (typeof registerValues['city'] != "undefined" && registerValues['city'] != "")
        $("#inp_city").val(registerValues['city']);

    if (typeof registerValues['zipcode'] != "undefined" && registerValues['zipcode'] != "")
        $("#inp_zipcode").val(registerValues['zipcode']);

    if (typeof registerValues['address'] != "undefined" && registerValues['address'] != "")
        $("#inp_address").val(registerValues['address']);
}

function registerRender_4() {
    let data = String.raw `
    <div class="card text-white bg-warning mb-3">
        <div class="card-body text-center">
            <h4 class="card-title">Przetwarzanie danych...</h4>
            <p><i class="fas fa-cog fa-spin fa-4x"></i></p>
        </div>
    </div>`;

    $("#btnRegBack").attr("disabled", "disabled");
    $("#btnRegNext").attr("disabled", "disabled");

    $("#registerBox").html(data);

    showAlert(AlertType.NONE);
    changeProgress(100);
}

function registerRender_4Success(m) {
    let data = String.raw `
    <div class="card text-white bg-success mb-3">
        <div class="card-body text-center">
            <h4 class="card-title">Zarejestrowano pomyślnie!</h4>
            <h5 class="card-subtitle">` + m + `</h5>
        </div>
    </div>`;

    $("#btnRegBack").attr("disabled", "disabled");
    $("#btnRegNext").attr("disabled", "disabled");

    $("#registerBox").html(data);

    showAlert(AlertType.NONE);
    changeProgress(100);
}

function registerRender_4Error(m = 'Spróbuj ponownie później!') {
    let data = String.raw `
    <div class="card text-white bg-danger mb-3">
        <div class="card-body text-center">
            <h4 class="card-title">Wystąpił błąd podczas rejestracji!</h4>
            <h5 class="card-subtitle">` + m + `</h5>
        </div>
    </div>`;

    $("#btnRegBack").removeAttr("disabled");
    $("#btnRegNext").attr("disabled", "disabled");

    $("#registerBox").html(data);

    showAlert(AlertType.NONE);
    changeProgress(100);
}

function systemSignUp() {

    registerValues['grecaptcha'] = verifyToken;

    $.ajax({
        url: "/system/signUp",
        method: "POST",
        data: toObject(registerValues),
        success: function (data) {
            try {
                if (data.success == true) {
                    registerRender_4Success(data.msg);
                    setTimeout(function () {
                        window.location.href = "/logowanie";
                    }, 2500);
                } else {
                    generateRecaptchaToken('login');

                    registerRender_4Error(data.msg);
                }

            } catch (e) {
                registerRender_4Error();
            }
        },
        error: function () {
            registerRender_4Error();
        }
    });
}


function changeNextButton(a = '') {
    if (a == '')
        a = 'Dalej <i class="fas fa-arrow-right"></i>';
    $("#btnRegNext").html(a);
}

function changeProgress(val) {
    $("#progressStep").css("width", val + "%");
}