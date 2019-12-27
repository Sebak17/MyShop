$(document).ready(function () {

    bindButtons();

    bindIPs();
});

function bindButtons() {

    $("#inp_maintenanceMode").change(function() {
        changeMaintenance($(this).is(':checked'));
    });

    $("#btnMaintenanceAddIP").click(function() {
        addIPToWhitelist();
    });

}

function changeMaintenance(enabled) {
    enabled = (enabled ? 1 : 0);

    let msg = $('#inp_maintenanceMsg').val();

    $.ajax({
        url: "/systemAdmin/settingsMaintenanceChange",
        method: "POST",
        data: {
            enabled: enabled,
            msg: msg,
        },
        success: function (data) {
            if(data.success == true) {
                location.reload();
            }
        },
        error: function () {
        },
    });
}

function addIPToWhitelist() {

    let ip = $("#inp_maintenanceAddIP").val();

    if(!validateIP(ip)) {
        showAlert(AlertType.ERROR, Lang.IP_NOT_CORRECT, '#alert01');
        return;
    }

    showAlert(AlertType.LOADING, Lang.IP_ADDING, '#alert01');
    $.ajax({
        url: "/systemAdmin/settingsMaintenanceAddIP",
        method: "POST",
        data: {
            ip: ip,
        },
        success: function (data) {
            if(data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.IP_ADDED, '#alert01');
                $("#inp_maintenanceAddIP").val('');
                showIPsList(data.list);
            } else {
                if(data.msg != null)
                    showAlert(AlertType.ERROR, data.msg, '#alert01');
                else
                    showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert01');
            }
        },
        error: function () {
        },
    });
}

function deleteIPFromWhitelist(ip) {
    if(!validateIP(ip)) {
        return;
    }

    $.ajax({
        url: "/systemAdmin/settingsMaintenanceDelIP",
        method: "POST",
        data: {
            ip: ip,
        },
        success: function (data) {
            if(data.success == true) {
                showIPsList(data.list);
            }
        },
        error: function () {
        },
    });
}

function bindIPs() {
     let btns = document.querySelectorAll('[data-addressIP]');

     btns.forEach(function (item, index) {

        $(item).click(function() {
            deleteIPFromWhitelist($(item).attr('data-addressIP'));
        });

     });
}

function showIPsList(list = []) {
    let m = "";

    list.forEach(function (item, index) {
        
        m += String.raw`
            <li class="list-group-item">` + item +  `
            <button type="button" class="btn btn-danger btn-sm float-right" data-addressIP="` + item +  `" ` + (item == '127.0.0.1' ? "disabled" : "") +  `><i class="fas fa-times"></i></li>
        `;

    });

    $("#maintenanceListIP").html(m);

    bindIPs();

}