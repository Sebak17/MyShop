var filtersValues = [],
    sortType = 1,
    categoryID = 0;

$(document).ready(function () {

    parseURL();

    $("#btnFiltersApply").click(function () {
        applyFilters();
    });

    $("#sortType").change(function () {
        sortType = parseInt($("#sortType").val());
    });

    bindCategoryList();
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
        filtersValues['product-price-min'] = url.searchParams.get("price-min");
        $("#fl_price1").val(filtersValues['product-price-min']);
    }
    if (url.searchParams.get("price-max")) {
        filtersValues['product-price-max'] = url.searchParams.get("price-max");
        $("#fl_price2").val(filtersValues['product-price-max']);
    }
}

function applyFilters() {

    let p1 = parseFloat($("#fl_price1").val());
    if (!isNaN(p1))
        filtersValues['product-price-min'] = p1.toFixed(2);
    else
        delete filtersValues['product-price-min'];

    let p2 = parseFloat($("#fl_price2").val());
    if (!isNaN(p2))
        filtersValues['product-price-max'] = p2.toFixed(2);
    else
        delete filtersValues['product-price-max'];


    window.location.href = generateURL();
}

function generateURL() {
    let url = "";

    if (categoryID != -1)
        url += "&category=" + categoryID;

    if (sortType != 1)
        url += "&sort=" + sortType;

    if (filtersValues['product-price-min'])
        url += "&price-min=" + parseFloat(filtersValues['product-price-min']).toFixed(2);

    if (filtersValues['product-price-max'])
        url += "&price-max=" + parseFloat(filtersValues['product-price-max']).toFixed(2);

    if (filtersValues['string'])
        url += "&string=" + filtersValues['string'];


    url = url.replace("&", "?");

    return "/produkty/" + url;
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