window.onload = function () {

    loadCategories();
    loadProductInfo();

    $("#btn_productEdit").click(function () {
        productEdit();
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

function productEdit() {
    let d_name = $('#inp_name').val();
    let d_price = $('#inp_price').val();
    let d_description = $('#inp_description').val();
    let d_category = $('#inp_category').val();

    if (!validateProductName(d_name)) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_NAME_ERROR);
        return;
    }

    if (!validateProductPrice(d_price)) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_PRICE_ERROR);
        return;
    }

    if (!validateProductDescription(d_description)) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_DESCRIPTION_ERROR);
        return;
    }

    if (!validateCategoryID(d_category)) {
        showAlert(AlertType.ERROR, Lang.CATEGORY_ID_ERROR);
        return;
    }

    showAlert(AlertType.LOADING, Lang.FORM_SENDING);

    $.ajax({
        url: "/systemAdmin/productEdit",
        method: "POST",
        data: {
            name: d_name,
            price: parseFloat(d_price),
            description: d_description,
            category: d_category,
        },
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.PRODUCT_FORM_EDIT_SUCCESS);
            } else {
                if (data.msg != null)
                    showAlert(AlertType.ERROR, data.msg);
                else
                    showAlert(AlertType.ERROR, Lang.PRODUCT_FORM_EDIT_ERROR);
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.PRODUCT_FORM_EDIT_ERROR);
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

function loadProductInfo() {
    $.ajax({
        url: "/systemAdmin/productLoadCurrent",
        method: "POST",
        success: function (data) {
            if (data.success == true) {

                let product = data.product;
                let images = product.images;

                $('#inp_name').val(product.name);
                $('#inp_price').val(product.price);
                $('#inp_description').val(product.description);
                $('#inp_category').val(product.category_id);

                parseImages(images);
                bindImagesRemove();
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
        url: "/systemAdmin/productEditImageAdd",
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.success == true) {
                loadImages();
            }
        },
        error: function () {}
    });
}

function loadImages() {

    $.ajax({
        url: "/systemAdmin/productEditImageList",
        method: "POST",
        data: {
            name: $(this).attr("data-btn-remove"),
        },
        success: function (data) {
            if (data.success == true) {
                parseImages(data.images);
                bindImagesRemove();
            }
        },
        error: function () {}
    });
}

function parseImages(images) {
    let imgBox = '';

    images.forEach(function (item, index) {
        imgBox += String.raw `<tr>
                                             <td>
                                                 <img class="img-fluid" width="196px" height="146px" src="/storage/products_images/` + item + `">
                                             </td>
                                             <td class="align-middle">
                                                 <button class="btn btn-danger" data-btn-remove="` + item + `"><i class="fas fa-minus"></i> Usuń zdjęcie</button>
                                             </td>
                                         </tr>`;
    });
    $('#tableImageList').html(imgBox);
}

function bindImagesRemove() {

    $("[data-btn-remove]").each(function (index) {
        $(this).click(function () {
            $.ajax({
                url: "/systemAdmin/productEditImageRemove",
                method: "POST",
                data: {
                    name: $(this).attr("data-btn-remove"),
                },
                success: function (data) {
                    if (data.success == true) {
                        loadImages();
                    }
                },
                error: function () {}
            });
        });
    });

}