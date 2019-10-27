var imageIndexs = 1,
    imageFiles = [];

window.onload = function () {

    loadCategories();
    loadProductImages();

    $("#btn_productAdd").click(function () {
        productAdd();
    });

    $("#btnImageInput").click(function (event) {
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
    let d_price = $('#inp_price').val();
    let d_description = $('#inp_description').val();
    let d_category = $('#inp_category').val();

    if(!validateProductName(d_name)) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_NAME_ERROR);
        return;
    }

    if(!validateProductPrice(d_price)) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_PRICE_ERROR);
        return;
    }

    if(!validateProductDescription(d_description)) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_DESCRIPTION_ERROR);
        return;
    }

    if(!validateCategoryID(d_category)) {
        showAlert(AlertType.ERROR, Lang.CATEGORY_ID_ERROR);
        return;
    }

    showAlert(AlertType.LOADING, Lang.FORM_SENDING);

    $.ajax({
        url: "/systemAdmin/productCreate",
        method: "POST",
        data: {
            name: d_name,
            price: parseFloat(d_price),
            description: d_description,
            category: d_category,
        },
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.PRODUCT_FORM_ADD_SUCCESS);

                setTimeout(function () {
                    window.location.href = "/admin/produkty/lista";
                }, 1000);
            } else {
                if (data.msg != null)
                    showAlert(AlertType.ERROR, data.msg);
                else
                    showAlert(AlertType.ERROR, Lang.PRODUCT_FORM_ADD_ERROR);
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.PRODUCT_FORM_ADD_ERROR);
        }
    });
}

function loadCategories() {
    $.ajax({
        url: "/systemAdmin/categoryList",
        method: "POST",
        success: function (data) {
            if (data.success == true) {

                let list = "";

                for (idx in data.list1) {
                    let obj = data.list1[idx];

                    let overName = "";

                    if (typeof obj.overcategory !== 'undefined') {
                        for (s_idx in data.list1) {
                            let s_obj = data.list1[s_idx];
                            if (s_obj.id == obj.overcategory)
                                overName = s_obj.name + " > ";
                        }
                    }


                    list += String.raw `<option value="` + obj.id + `">` + overName + obj.name + `</option>`;

                }


                $('#inp_category').html(list);
            }
        },
        error: function () {}
    });
}

function loadProductImages() {
    $.ajax({
        url: "/systemAdmin/productLoadOldImages",
        method: "POST",
        success: function (data) {
            if (data.success == true) {

                let boxes = '';

                for (let i = 0; i < data.images.length; i++) {

                    boxes += String.raw `<tr id="rowImage_` + i + `">
                                            <td>
                                                <img class="img-fluid" width="196px" height="146px" src="/storage/tmp_images/` + data.images[i] + `">
                                            </td>
                                            <td class="align-middle">
                                                <button class="btn btn-danger"><i class="fas fa-minus"></i> Usuń zdjęcie</button>
                                            </td>
                                        </tr>`;

                }
                $('#tableImageList').html(boxes);
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
                loadProductImages();
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