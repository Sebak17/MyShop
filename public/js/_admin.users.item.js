var _userID = -1;

$(document).ready(function () {

    _userID = $("[data-id]").attr('data-id');

    bindButtons();

    changeHistoryList();

    $("#inp_data_district").val(_userDisctrict);
});

function bindButtons() {

    $('#btnShowHistory').click(function () {
        $('#modalHistory').modal('show');
    });

    $('#btnBanModal').click(function () {
        $('#modalBan').modal('show');
    });

    $('#btnChangePersonalModal').click(function () {
        $('#modalChangePersonal').modal('show');
    });

    $('#btnChangeLocationModal').click(function () {
        $('#modalChangeLocation').modal('show');
    });

    $("#modalHistoryType").change(function () {
        changeHistoryList(this.value);
    });

    $("#btnBanUser").click(function() {
    	banUser();
    });

    $("#btnUnban").click(function() {
    	unbanUser();
    });

    $("#btnChangePersonal").click(function() {
        changeUserPersonal();
    });

    $("#btnChangeLocation").click(function() {
        changeUserLocation();
    });
}

function changeHistoryList(type = 'ALL') {

    let list = "";

    historyData.forEach(function (item, index) {
        
    	if(type != 'ALL' && item.type != type)
    		return;

        list += String.raw`
        <tr>
	        <td>` + item.data + `</td>
	        <td>` + item.typeName + `</td>
	        <td>` + item.ip + `</td>
	        <td>` + item.time + `</td>
        </tr>
        `;

    });

    $("#modalHistoryBox").html(list);
}

function banUser() {
	if(isBanned)
		return;

	let reason = $("#inp_BanInfo").val();

	if(!validateBanDescription(reason)) {
		 showAlert(AlertType.ERROR, Lang.BAN_REASON_ERROR, '#alert01');
		return;
	}

	$.ajax({
        url: "/systemAdmin/userBan",
        method: "POST",
        data: {
            id: _userID,
            reason: reason,
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.USER_BAN_SUCCESS, '#alert01');

                    setTimeout(function (argument) {
                        location.reload();
                    }, 800);
                } else {
                    showAlert(AlertType.ERROR, data.msg, '#alert01');
                }
            } catch (e) {
                showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert01');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert01');
        }
    });
}

function unbanUser() {
	if(!isBanned)
		return;

	$.ajax({
        url: "/systemAdmin/userUnban",
        method: "POST",
        data: {
            id: _userID,
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    location.reload();
                }
            } catch (e) {
            }
        },
        error: function () {
        }
    });
}


function changeUserPersonal() {

    let d_fname = $("#inp_data_fname").val();
    let d_sname = $("#inp_data_sname").val();
    let d_phone = $("#inp_data_phone").val();

    d_fname = d_fname.replace(/\s+/g, "");
    d_sname = d_sname.replace(/\s+/g, "");
    d_phone = d_phone.replace(/\s+/g, "");

    if (!validatePersonalNameLength(d_fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_NOT_IN_RANGE, '#alert02');
        return;
    }

    if (!validatePersonalNameString(d_fname)) {
        showAlert(AlertType.ERROR, Lang.FIRSTNAME_ONLY_LETTERS, '#alert02');
        return;
    }

    if (!validatePersonalNameLength(d_sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_NOT_IN_RANGE, '#alert02');
        return;
    }

    if (!validatePersonalNameString(d_sname)) {
        showAlert(AlertType.ERROR, Lang.SURNAME_ONLY_LETTERS, '#alert02');
        return;
    }

    if (!validatePhone(d_phone)) {
        showAlert(AlertType.ERROR, Lang.PHONENUMBER_INCORRECT, '#alert02');
        return;
    }

    showAlert(AlertType.LOADING, Lang.SETTINGS_DATA_IN_PROGRESS, '#alert02');

    $.ajax({
        url: "/systemAdmin/userChangePersonal",
        method: "POST",
        data: {
            id: _userID,
            fname: d_fname,
            sname: d_sname,
            phone: d_phone
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.SETTINGS_DATA_SUCCESS, '#alert02');

                    setTimeout(function (argument) {
                        location.reload();
                    }, 800);
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
        }
    });
}

function changeUserLocation() {
    let d_district = $("#inp_data_district").val();
    let d_city = $("#inp_data_city").val();
    let d_zipcode = $("#inp_data_zipcode").val();
    let d_address = $("#inp_data_address").val();

    d_zipcode = d_zipcode.replace(/\s+/g, "");

    if (!validateDistrict(d_district)) {
        showAlert(AlertType.ERROR, Lang.DISTRICT_INCORRECT, '#alert03');
        return;
    }

    if (!validateCityLength(d_city)) {
        showAlert(AlertType.ERROR, Lang.CITY_NOT_IN_RANGE, '#alert03');
        return;
    }

    if (!validateCityString(d_city)) {
        showAlert(AlertType.ERROR, Lang.CITY_NAME_INCORRECT, '#alert03');
        return;
    }

    if (!validateZipCode(d_zipcode)) {
        showAlert(AlertType.ERROR, Lang.ZIPCODE_INCORRECT, '#alert03');
        return;
    }

    if (!validateAdressLength(d_address)) {
        showAlert(AlertType.ERROR, Lang.ADDRESS_NOT_IN_RANGE, '#alert03');
        return;
    }

    if (!validateAdressString(d_address)) {
        showAlert(AlertType.ERROR, Lang.ADDRESS_NAME_INCORRECT, '#alert03');
        return;
    }

    showAlert(AlertType.LOADING, Lang.SETTINGS_DATA_IN_PROGRESS, '#alert03');

    $.ajax({
        url: "/systemAdmin/userChangeLocation",
        method: "POST",
        data: {
            id: _userID,
            district: d_district,
            city: d_city,
            zipcode: d_zipcode,
            address: d_address
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.SETTINGS_DATA_SUCCESS, '#alert03');

                    setTimeout(function (argument) {
                        location.reload();
                    }, 800);
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
        }
    });
}