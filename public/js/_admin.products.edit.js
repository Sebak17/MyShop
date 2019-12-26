var productParams = [];
var _productID = -1;

window.onload = function () {

    _productID = $("[data-id]").attr('data-id');

    loadCategories();
    loadProductInfo();

    $("#btn_productEdit").click(function () {
        productEdit();
    });

    $("#btnImageInput").click(function (event) {
        $("#productUpload").trigger('click');
    });

    $('#btnParamAddModal').click(function () {
        $('#modalParamAdd').modal('show');
    });

    $('#btnParamAdd').click(function () {
        productAddParam();
    });

    $("#productUpload").change(function (event) {
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

    let d_params = JSON.stringify(productParams);

    showAlert(AlertType.LOADING, Lang.FORM_SENDING);

    $.ajax({
        url: "/systemAdmin/productEdit",
        method: "POST",
        data: {
            id: _productID,
            name: d_name,
            price: parseFloat(d_price),
            description: d_description,
            category: d_category,
            status: $('#inp_status').val(),
            params: d_params,
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

function productAddParam() {
    let d_name = $('#fmParamAddName').val();
    let d_value = $('#fmParamAddValue').val();

    let exist = false;

    productParams.forEach(function (item, index) {
        if (d_name.toLowerCase() == item.name.toLowerCase())
            exist = true;
    });

    if (exist) {
        showAlert(AlertType.ERROR, Lang.PRODUCT_PARAM_EXIST_ERROR, '#alert02');
        return;
    }

    if(!validateStringSimple(d_name)){
        showAlert(AlertType.ERROR, Lang.PRODUCT_PARAM_NAME_ERROR, '#alert02');
        return;
    }

    if(!validateStringSimple(d_value)){
            showAlert(AlertType.ERROR, Lang.PRODUCT_PARAM_VALUE_ERROR, '#alert02');
        return;
    }


    productParams.push({
        name: d_name,
        value: d_value,
    });

    $('#modalParamAdd').modal('hide');
    $('#fmParamAddName').val('');
    $('#fmParamAddValue').val('');

    bindParams();

}

function bindParams() {

    let m = '';

    productParams.forEach(function (item, index) {
        m += String.raw `<tr>
                            <td>` + item.name + `</td>
                            <td>` + item.value + `</td>
                            <td class="text-right">
                                <button data-paramID="` + index + `" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
                            </td>
                        </tr>`;

    });

    $('#productParams').html(m);


    $("[data-paramID]").each(function (index) {
        let v = parseInt(this.getAttribute('data-paramID'));
        if (isNaN(v))
            return;

        $(this).click(function () {

            if (!confirm("Czy na pewno chcesz usunąć ten parametr?"))
                return;

            let param = productParams[v];

            if(typeof param === 'undefined')
                return;

            productParams = productParams.filter(function (item) {
                return item.name != param.name;
            });

            bindParams();
        });
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
        data: {
            id: _productID,
        },
        success: function (data) {
            if (data.success == true) {

                let product = data.product;
                let images = product.images;

                $('#inp_category').val(product.category_id);
                $('#inp_status').val(product.status);

                if(JSON.parse(product.params) != null)
                    productParams = JSON.parse(product.params);

                parseImages(images);
                bindImagesRemove();

                bindParams();
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

    formData.append('id', _productID,);

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
            id: _productID,
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
                    id: _productID,
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