function loadSite_Settings() {
    $("#btn_changeData0").click(function() {
        settings_changeData0();
    });

    $("#btn_changeData1").click(function() {
        settings_changeData1();
    });

    $("#btn_changePass").click(function() {
        settings_changePass();
    });
}

function settings_changeData0() {

	let d_fname = $("#inp_data_fname").val();
	let d_sname = $("#inp_data_sname").val();
	let d_phone = $("#inp_data_phone").val();

	d_fname = d_fname.replace(/\s+/g, "");
    d_sname = d_sname.replace(/\s+/g, "");

    if (d_fname.length < 3 || d_fname.length > 16) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Imię ma błędną długość! Poprawna długość: 3-16`, '#alert01');
        return;
    }

    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/.test(d_fname)) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Imię może zawierać tylko litery!`, '#alert01');
        return;
    }

    if (d_sname.length < 3 || d_sname.length > 16) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Nazwisko ma błędną długość! Poprawna długość: 3-16`, '#alert01');
        return;
    }

    if (!/^[a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ]+$/.test(d_sname)) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Nazwisko może zawierać tylko litery!`, '#alert01');
        return;
    }

    if (d_phone.length != 9 || isNaN(parseInt(d_phone))) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Błędny numer telefonu!`, '#alert01');
        return;
    }

	showAlert(2, String.raw`<i class="fas fa-circle-notch fa-spin"></i> Trwa zmiana danych...`, '#alert01');

	$.ajax({
        url: "/system/panelChangeDataPersonal",
        method: "POST",
        data: {
			fname: d_fname,
			sname: d_sname,
			phone: d_phone
        },
        success: function(data) {
            try {
                data = JSON.parse(data);

                if (data.success == true) {
                    showAlert(0, String.raw`<i class="fas fa-check"></i> Dane zostały zmienione!`, '#alert01');
					window.location.href = "/panel/ustawienia";
                } else {
                    if (data.msg != '')
                        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> ` + data.msg, '#alert01');
                    else
                        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert01');
                }
            } catch (e) {
                showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert01');
            }
        },
        error: function() {
            showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert01');
        }
    });
}

function settings_changeData1() {

	let d_district = $("#inp_data_district").val();
    let d_city = $("#inp_data_city").val();
    let d_zipcode = $("#inp_data_zipcode").val();
    let d_address = $("#inp_data_address").val();

	d_zipcode = d_zipcode.replace(/\s+/g, "");

    if (d_district > 16 || d_district < 1) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Błędne województwo!`);
        return;
    }

    if (d_city.length < 2 || d_city.length > 32) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Miasto ma błędną długość! Poprawna długość: 2-32`, '#alert02');
        return;
    }

    if (!/\b\d{2}-\d{3}\b/.test(d_zipcode)) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Błędny kod pocztowy!`, '#alert02');
        return;
    }

    if (d_address.length < 4 || d_address.length > 40) {
        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Adres ma błędną długość! Poprawna długość: 2-40`, '#alert02');
        return;
    }

	showAlert(2, String.raw`<i class="fas fa-circle-notch fa-spin"></i> Trwa zmiana danych...`, '#alert02');

	$.ajax({
        url: "/system/panelChangeDataLocation",
        method: "POST",
        data: {
			district: d_district,
			city: d_city,
			zipcode: d_zipcode,
			address: d_address
        },
        success: function(data) {
            try {
                data = JSON.parse(data);

                if (data.success == true) {
                    showAlert(0, String.raw`<i class="fas fa-check"></i> Dane zostały zmienione!`, '#alert02');
					window.location.href = "/panel/ustawienia";
                } else {
                    if (data.msg != '')
                        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> ` + data.msg, '#alert02');
                    else
                        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert02');
                }
            } catch (e) {
                showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert02');
            }
        },
        error: function() {
            showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert02');
        }
    });
}

function settings_changePass() {

    let d_pass0 = $("#inp_pass0").val();
    let d_pass1 = $("#inp_pass1").val();
    let d_pass2 = $("#inp_pass2").val();

    if (d_pass1.length < 4 || d_pass1.length > 20) {
        showAlert(1, String.raw`<i class="fas fa-check"></i> Hasło ma błędną długość! Poprawna długość: 4-20`, '#alert03');
        return;
    }

    if (d_pass1 !== d_pass2) {
        showAlert(1, String.raw`<i class="fas fa-check"></i> Hasła nie zgadzają się!`, '#alert03');
        return;
    }

    showAlert(2, String.raw`<i class="fas fa-circle-notch fa-spin"></i> Trwa zmienianie hasła...`, '#alert03');

    $.ajax({
        url: "/system/panelChangePassword",
        method: "POST",
        data: {
            pass0: d_pass0,
            pass1: d_pass1
        },
        success: function(data) {
            try {
                data = JSON.parse(data);

                if (data.success == true) {
                    $("#inp_pass0").val("");
                    $("#inp_pass1").val("");
                    $("#inp_pass2").val("");
                    showAlert(0, String.raw`<i class="fas fa-check"></i> Hasło zostało zmienione!`, '#alert03');
                } else {
                    if (data.msg != '')
                        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> ` + data.msg, '#alert03');
                    else
                        showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert03');
                }
            } catch (e) {
                showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert03');
            }
        },
        error: function() {
            showAlert(1, String.raw`<i class="fas fa-exclamation"></i> Wystąpił błąd podczas zmieniania!`, '#alert03');
        }
    });

}

function settings_selectDistrict(id) {
    $("#inp_data_district").val(id);
}
