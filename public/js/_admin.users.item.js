var _userID = -1;

$(document).ready(function () {

    _userID = $("[data-id]").attr('data-id');

    bindButtons();

    changeHistoryList();
});

function bindButtons() {

    $('#btnShowHistory').click(function () {
        $('#modalHistory').modal('show');
    });

    $('#btnBanModal').click(function () {
        $('#modalBan').modal('show');
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