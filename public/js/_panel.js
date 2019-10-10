function loadSite_Settings() {
    $("#btn_changeDataPersonal").click(function () {
        settings_changeDataPersonal();
    });

    $("#btn_changeDataLocation").click(function () {
        settings_changeDataLocation();
    });

    $("#btn_changePass").click(function () {
        settings_changePassword();
    });
}

function settings_changeDataPersonal() {

    if (isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS, '#alert01');
        return;
    }

    let d_fname = $("#inp_data_fname").val();
    let d_sname = $("#inp_data_sname").val();
    let d_phone = $("#inp_data_phone").val();

    d_fname = d_fname.replace(/\s+/g, "");
    d_sname = d_sname.replace(/\s+/g, "");
    d_phone = d_phone.replace(/\s+/g, "");

    if (!validatePersonalNameLength(d_fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_NOT_IN_RANGE, '#alert01');
        return;
    }

    if (!validatePersonalNameString(d_fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_ONLY_LETTERS, '#alert01');
        return;
    }

    if (!validatePersonalNameLength(d_sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_NOT_IN_RANGE, '#alert01');
        return;
    }

    if (!validatePersonalNameString(d_sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_ONLY_LETTERS, '#alert01');
        return;
    }

    if (!validatePhone(d_phone)) {
        showAlert(AlertType.ERROR, Lang.PHONENUMBER_INCORRECT, '#alert01');
        return;
    }

    isRequest = true;
    showAlert(AlertType.LOADING, Lang.SETTINGS_DATA_IN_PROGRESS, '#alert01');

    $.ajax({
        url: "/systemUser/changeDataPersonal",
        method: "POST",
        data: {
            fname: d_fname,
            sname: d_sname,
            phone: d_phone
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.SETTINGS_DATA_SUCCESS, '#alert01');
                    window.location.href = "/panel/ustawienia";
                } else {
                    if (typeof data.msg != 'undefined' && data.msg != '')
                        showAlert(AlertType.ERROR, data.msg, '#alert01');
                    else
                        showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert01');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert01');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert01');
        },
        complete: function () {
            isRequest = false;
        }
    });
}

function settings_changeDataLocation() {

    if (isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS, '#alert02');
        return;
    }

    let d_district = $("#inp_data_district").val();
    let d_city = $("#inp_data_city").val();
    let d_zipcode = $("#inp_data_zipcode").val();
    let d_address = $("#inp_data_address").val();

    d_zipcode = d_zipcode.replace(/\s+/g, "");

    if (!validateDistrict(d_district)) {
        showAlert(AlertType.ERROR, Lang.DISTRICT_INCORRECT, '#alert02');
        return;
    }

    if (!validateCityLength(d_city)) {
        showAlert(AlertType.ERROR, Lang.CITY_NOT_IN_RANGE, '#alert02');
        return;
    }

    if (!validateCityString(d_city)) {
        showAlert(AlertType.ERROR, Lang.CITY_NAME_INCORRECT, '#alert02');
        return;
    }

    if (!validateZipCode(d_zipcode)) {
        showAlert(AlertType.ERROR, Lang.ZIPCODE_INCORRECT, '#alert02');
        return;
    }

    if (!validateAdressLength(d_address)) {
        showAlert(AlertType.ERROR, Lang.ADDRESS_NOT_IN_RANGE, '#alert02');
        return;
    }

    if (!validateAdressString(d_address)) {
        showAlert(AlertType.ERROR, Lang.ADDRESS_NAME_INCORRECT, '#alert02');
        return;
    }

    isRequest = true;
    showAlert(AlertType.LOADING, Lang.SETTINGS_DATA_IN_PROGRESS, '#alert02');

    $.ajax({
        url: "/systemUser/changeDataLocation",
        method: "POST",
        data: {
            district: d_district,
            city: d_city,
            zipcode: d_zipcode,
            address: d_address
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.SETTINGS_DATA_SUCCESS, '#alert02');
                    window.location.href = "/panel/ustawienia";
                } else {
                    if (typeof data.msg != 'undefined' && data.msg != '')
                        showAlert(AlertType.ERROR, data.msg, '#alert02');
                    else
                        showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert02');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert02');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert02');
        },
        complete: function () {
            isRequest = false;
        }
    });
}

function settings_changePassword() {

    if (isRequest) {
        showAlert(AlertType.ERROR, Lang.REQUEST_IN_PROGRESS, '#alert03');
        return;
    }

    let d_pass_old = $("#inp_pass0").val();
    let d_pass_new1 = $("#inp_pass1").val();
    let d_pass_new2 = $("#inp_pass2").val();

    if (!validatePassword(d_pass_new1)) {
        showAlert(AlertType.ERROR, Lang.PASSWORD_NOT_IN_RANGE, '#alert03');
        return;
    }

    if (d_pass_new1 !== d_pass_new2) {
        showAlert(AlertType.ERROR, Lang.PASSWORDS_NOT_MATCH,'#alert03');
        return;
    }

    if(d_pass_old === d_pass_new1) {
        showAlert(AlertType.ERROR, Lang.PASSWORDS_IDENTICAL, '#alert03');
        return;
    }

    isRequest = true;
    showAlert(AlertType.LOADING, Lang.SETTINGS_PASSWORD_IN_PROGRESS, '#alert03');

    $.ajax({
        url: "/systemUser/changePassword",
        method: "POST",
        data: {
            password_old: d_pass_old,
            password_new: d_pass_new1
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    $("#inp_pass0").val("");
                    $("#inp_pass1").val("");
                    $("#inp_pass2").val("");
                    showAlert(AlertType.SUCCESS, Lang.SETTINGS_PASSWORD_SUCCESS, '#alert03');

                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else {
                    if (typeof data.msg != 'undefined' && data.msg != '')
                        showAlert(AlertType.ERROR, data.msg, '#alert03');
                    else
                        showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert03');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert03');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.SETTINGS_DATA_ERROR, '#alert03');
        },
        complete: function () {
            isRequest = false;
        }
    });

}

function settings_selectDistrict(id) {
    $("#inp_data_district").val(id);
}