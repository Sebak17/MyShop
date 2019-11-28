$(document).ready(function () {

    let info = $("#lockerInfo");

    if ($("#lockerInfo").length)
        loadLockerInfo(info, $("#lockerCode").html());


    bindKeys();
});

function bindKeys() {
	$("#btnPayCancel").click(function() {
		payment_cancel();	
	});
}

function payment_cancel() {
	let id = parseInt($("[data-order-id]").attr('data-order-id'));

	if(isNaN(id)) {
		return;
	}

	$.ajax({
        url: "/systemUser/paymentCancel",
        type: "POST",
        data: {
        	id: id
        },
        success: function (data) {
        	if(data.success == true)
        		location.reload();

        },
        error: function () {},
    });
}

function loadLockerInfo(info, code) {

    $.ajax({
        url: "https://api-shipx-pl.easypack24.net/v1/points/" + code,
        type: "GET",
        contentType: 'application/json; charset=utf-8',
        success: function (data) {
            let address = String.raw `
            	<div class="row text-left ml-2">
                    <div class="col-6 lead">Adres paczkomatu: </div>
                    <div class="col-6 lead"><strong>ul. ` + data.address.line1 + " " + data.address.line2 + `</strong></div>
                </div>`;

            info.html(address);

            $("#btnShowLocLocker").click(function () {
            	let win = window.open("https://www.google.com/maps/search/" + data.location.latitude + "+" + data.location.longitude, '_blank');
  				win.focus();
            });
        },
        error: function () {},
    });
}