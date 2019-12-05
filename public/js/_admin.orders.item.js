var _currentDeliver = '',
    _currentLocker = '';
var lockerMap;

$(document).ready(function() {
	
	bindButtons();

});

function bindButtons() {
	
	$('#btnShowHistory').click(function() {
		$('#modalHistory').modal('show');
	});

	$('#btnChangeStatusModal').click(function() {
		$('#modalChangeStatus').modal('show');
	});

	$('#btnChangeDeliverLocModal').click(function() {
		$('#modalChangeDeliverLoc').modal('show');
	});

	$("#inp_orderDeliverType").each(function (index) {
        $(this).change(function () {
            let val = $(this).val();
            changeDeliver(val);
        });
    });

    $("#btnChangeDeliverLoc").click(function() {
		changeDeliverLoc();    	
    });


	$('#btnChangeStatus').click(function() {
		changeOrderStatus();
	});

}

function changeOrderStatus() {

	let id = $("[data-id]").attr('data-id');
	if(isNaN(id))
		return;

	let status = $("#inp_orderNewStatus").val();

	if(status == null)
		return;

	$.ajax({
        url: "/systemAdmin/orderChangeStatus",
        method: "POST",
        data: {
            id: id,
            status: status,
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.ORDER_STATUS_SUCCESS, '#alert01');

                    setTimeout(function (argument) {
                    	location.reload();
                    }, 1000);
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

function changeDeliver(deliver) {

    _currentDeliver = deliver;

    $("#modalDeliverLoc_Locker").addClass('d-none');
    $("#modalDeliverLoc_Courier").addClass('d-none');

    switch (deliver) {
        case 'INPOST_LOCKER':
            $("#modalDeliverLoc_Locker").removeClass('d-none');
            if (lockerMap != null) {
                return;
            }

            lockerMap = easyPack.mapWidget('easypack-map', function (point) {
                if (point == null)
                    return;

                _currentLocker = point.name;

                $("#dataLockerName").html(point.name);
                $("#dataLockerAddress").html("ul. " + point.address.line1 + " " + point.address.line2);
            });

            break;
        case 'COURIER':
            $("#modalDeliverLoc_Courier").removeClass('d-none');
            break;
    }
}

function changeDeliverLoc() {

	let id = $("[data-id]").attr('data-id');
	if(isNaN(id))
		return;

	if (_currentDeliver == '') {
        showAlert(AlertType.ERROR, Lang.ORDER_DELIVER_CHOOSE, '#alert02');
        return;
    }

    let m_deliver = [];

    if (_currentDeliver == 'INPOST_LOCKER') {
        if (_currentLocker == '') {
            showAlert(AlertType.ERROR, Lang.ORDER_DELIVER_LOCKER, '#alert02');
            return;
        }

        m_deliver['type'] = "INPOST_LOCKER";
        m_deliver['lockerName'] = _currentLocker;
    }

    if (_currentDeliver == 'COURIER') {


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


        m_deliver['type'] = "COURIER";
        m_deliver['district'] = d_district;
        m_deliver['city'] = d_city;
        m_deliver['zipcode'] = d_zipcode;
        m_deliver['address'] = d_address;
    }

    let orderData = [];

    orderData['id'] = id;
    orderData['deliver'] = toObject(m_deliver);

    showAlert(AlertType.LOADING, Lang.ORDER_CONFIRMING);

    $.ajax({
        url: "/systemAdmin/orderChangeDeliverLoc",
        method: "POST",
        data: toObject(orderData),
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.ORDER_CONFIRMING_SUCCESS);
            } else
                showAlert(AlertType.ERROR, Lang.ORDER_CONFIRMING_ERROR);
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.ORDER_CONFIRMING);
        }
    });
}

window.easyPackAsyncInit = function () {

    easyPack.init({
        defaultLocale: 'pl',
        mapType: 'osm',
        searchType: 'osm',
        points: {
            types: ['parcel_locker']
        },
        map: {
            initialTypes: ['parcel_locker']
        }
    });
}