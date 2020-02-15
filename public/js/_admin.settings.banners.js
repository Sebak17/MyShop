$(document).ready(function () {

    bindButtons();

    bindImages();
});

function bindButtons() {

    $("#btnAddBanner").click(function() {
        $("#imageUpload").trigger('click');
    });

    $("#imageUpload").change(function (event) {
        if (this.files && this.files.length > 0) {
            uploadImages(this);
        }
    });
}

function uploadImages(input) {
    let formData = new FormData();
    for (let i = 0; i < input.files.length; i++) {
        formData.append('images[' + i + ']', input.files[i]);
    }

    $.ajax({
        url: "/systemAdmin/settingsBannersUploadImage",
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                location.reload();
            } else {
                if(data.msg != null)
                    showAlert(AlertType.ERROR, data.msg, '#alert');
                else
                    showAlert(AlertType.ERROR, Lang.FORM_SENDING_ERROR, '#alert');
            }
        },
        error: function () {}
    });
}

function bindImages() {
    $("[data-btn-remove]").each(function (index) {
        $(this).click(function () {
            $.ajax({
                url: "/systemAdmin/settingsBannersRemoveImage",
                method: "POST",
                data: {
                    name: $(this).attr("data-btn-remove"),
                },
                success: function (data) {
                    if (data.success == true) {
                        location.reload();
                    }
                },
                error: function () {}
            });
        });
    });
}