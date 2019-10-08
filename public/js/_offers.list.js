var filtersValues = [],
    sortType = 1,
    categoryID = 0;

$(document).ready(function () {

    parseURL();

    loadCategories();

    $("#btnFiltersApply").click(function () {
        applyFilters();
    });

    $("#sortType").change(function () {
        sortType = parseInt($("#sortType").val());
    });
});

function parseURL() {
    let url = new URL(window.location.href);

    if (url.searchParams.get("category")) {
        let id = url.searchParams.get("category");
        if (!isNaN(id))
            categoryID = id;
    }

    if (url.searchParams.get("string")) {
        filtersValues['string'] = url.searchParams.get("string");
        $("#searchBox").val(filtersValues['string']);
    }

    if (url.searchParams.get("sort")) {
        let sort = url.searchParams.get("sort");
        if (!isNaN(sort)) {
            sortType = parseInt(sort);
            $("#sortType").val(sortType);
        }
    }

    if (url.searchParams.get("price-min")) {
        filtersValues['offer-price-min'] = url.searchParams.get("price-min");
        $("#fl_price1").val(filtersValues['offer-price-min']);
    }
    if (url.searchParams.get("price-max")) {
        filtersValues['offer-price-max'] = url.searchParams.get("price-max");
        $("#fl_price2").val(filtersValues['offer-price-max']);
    }
}

function applyFilters() {

    let p1 = parseFloat($("#fl_price1").val());
    if (!isNaN(p1))
        filtersValues['offer-price-min'] = p1.toFixed(2);
    else
        delete filtersValues['offer-price-min'];

    let p2 = parseFloat($("#fl_price2").val());
    if (!isNaN(p2))
        filtersValues['offer-price-max'] = p2.toFixed(2);
    else
        delete filtersValues['offer-price-max'];


    window.location.href = generateURL();
}

function generateURL() {
    let url = "";

    if (categoryID != -1)
        url += "&category=" + categoryID;

    if (sortType != 1)
        url += "&sort=" + sortType;

    if (filtersValues['offer-price-min'])
        url += "&price-min=" + parseFloat(filtersValues['offer-price-min']).toFixed(2);

    if (filtersValues['offer-price-max'])
        url += "&price-max=" + parseFloat(filtersValues['offer-price-max']).toFixed(2);

    if (filtersValues['string'])
        url += "&string=" + filtersValues['string'];


    url = url.replace("&", "?");

    return "/oferty/" + url;
}

function loadCategories() {
    $.ajax({
        url: "/system/categoriesList",
        method: "POST",
        data: {
            id: categoryID
        },
        success: function (data) {
            try {
                if (data.success == true) {

                    let m = "";
                    for (ix in data.categories) {
                        let obj = data.categories[ix];

                        m += String.raw `<tr class="category category-item" category="` + obj.id + `"><td class="text-center"><i class="fas ` + obj.icon + ` fa-1x"></i></td><td>` + obj.name + `</td></tr>`;
                    }

                    $("#categoriesList2").html(m);

                    if (categoryID == 0)
                        $("#categoryBack").addClass('d-none');
                    else {
                        if (typeof data.overcategory != 'undefined') {
                            $("#prevCategory").html(data.overcategory.name);
                            $(".category-item-back").attr('category', data.overcategory.id);
                        }
                        $("#categoryBack").removeClass('d-none');
                    }




                    bindCategoryList();

                }
            } catch (e) {}
        },
        error: function () {}
    });


}

function loadOffers() {

    $.ajax({
        url: "/system/offersList",
        method: "POST",
        data: {
            filters: toObject(filtersValues),
            sortType: sortType,
            categoryID: categoryID
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    $("#offersList").html(data.offers);
                } else {
                    $("#offersList").html(String.raw `<div class="alert hidden" id="alert"></div>`);
                    showAlert(AlertType.ERROR, data.msg);
                }

            } catch (e) {
                $("#offersList").html(String.raw `<div class="alert hidden" id="alert"></div>`);
                showAlert(AlertType.ERROR, Lang.OFFERSLIST_ERROR_LOADING);
            }
        },
        error: function () {
            $("#offersList").html(String.raw `<div class="alert hidden" id="alert"></div>`);
            showAlert(AlertType.ERROR, Lang.OFFERSLIST_ERROR_LOADING);
        }
    });

}

function bindCategoryList() {
    let cat = document.querySelectorAll(".category-item");

    for (let i = 0; i < cat.length; i++) {
        cat[i].addEventListener('click', function () {
            categoryID = cat[i].getAttribute("category");

            window.location.href = generateURL();
        });

    }


    $(".category-item-back").click(function () {
        categoryID = $(".category-item-back").attr("category");

        window.location.href = generateURL();
    });
}