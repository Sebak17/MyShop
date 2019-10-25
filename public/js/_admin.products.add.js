var imageIndexs = 1,
    imageFiles = [];

window.onload = function () {

    loadPreviousImages();

    $("#btnAdd").click(function () {
        productAdd();
    });

    $("#offerImageInput").click(function (event) {
        $("#offerUpload").trigger('click');
    });

    $("#offerUpload").change(function (event) {
        if (this.files && this.files.length > 0) {
            uploadImages(this);
        }
    });

}

function productAdd() {

    let d_name = $('#inp_name').val();
    let d_description = $('#inp_description').val();
    let d_category = $('#inp_category').val();

    showAlert(AlertType.LOADING, Lang.FORM_SENDING);
}

function loadPreviousImages() {
    $.ajax({
        url: "/systemAdmin/productLoadOldImages",
        method: "POST",
        success: function (data) {
            if (data.success == true) {

                let boxes = '';

                for (let i = 0; i < data.images.length; i++) {

                    boxes += String.raw `<div class="offer-photo of-image ml-2" id="offerImage_` + i + `">
                            						<img src="/storage/tmp_images/` + data.images[i] + `" width="196px" height="146px">
                            					</div>`;

                }
                $('#imagesList').html(boxes);
            }
        },
        error: function () {}
    });
}

function uploadImages(input) {
    let formData = new FormData();
    for (let i = 0; i < input.files.length; i++) {
        formData.append('images[' + i + ']', input.files[i]);
    }

    $.ajax({
        url: "/systemAdmin/productAddImage",
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                $('#offerUpload').val('');

                let boxes = '';

                for (let i = 0; i < data.images.length; i++) {

                    boxes += String.raw `<div class="offer-photo of-image ml-2" id="offerImage_` + i + `">
                            						<img src="/storage/tmp_images/` + data.images[i] + `" width="196px" height="146px">
                            					</div>`;


                }
                $('#imagesList').html(boxes);
            }
        },
        error: function () {}
    });
}

function bindImages() {
    $("#imagesList").find('div').each(function () {
        let el = $(this);

        el.click(function () {
            alert(el.children('img').attr('src'));
        });
    });
}