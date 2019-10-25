var categoriesTree = {},
    categoriesList = [],
    selCategory = -1;

window.onload = function () {
    category_load();

    category_bindKeys();
}

function category_bindKeys() {
    $("#btnAdd").click(function () {
        category_btn_add();
    });

    $("#btnEdit").click(function () {
        category_btn_edit();
    });

    $("#btnRemove").click(function () {
        category_btn_remove();
    });

    $("#btnChangeOrder").click(function () {
        category_btn_changeOrder();
    });

    $("#btnModalListSave").click(function () {
        category_btn_listSave();
    });


}

function category_btn_add() {
    let name = $("#fmAddName").val();
    let icon = $("#fmAddIcon").val();
    let ovcat = $("#fmAddList").val();

    if (!validateCategoryName(name)) {
        showAlert(AlertType.ERROR, Lang.CATEGORY_NAME_ERROR, '#alert01');
        return;
    }

    if (!validateIconFA(icon)) {
        showAlert(AlertType.ERROR, Lang.ICON_FA_ERROR, '#alert01');
        return;
    }

    $.ajax({
        url: "/systemAdmin/categoryAdd",
        method: "POST",
        data: {
            name: name,
            icon: icon,
            ovcat: ovcat
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.CATEGORY_FORM_ADD_SUCCESS, '#alert01');
                    location.reload();
                } else
                if (data.msg != null)
                    showAlert(AlertType.ERROR, data.msg, '#alert01');
                else
                    showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_ADD_ERROR, '#alert01');

            } catch (e) {
                showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_ADD_ERROR, '#alert01');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_ADD_ERROR, '#alert01');
        }
    });
}

function category_btn_edit() {
    if (selCategory <= 0) {
        return;
    }

    let name = $("#fmEditName").val();
    let icon = $("#fmEditIcon").val();

    if (!validateCategoryName(name)) {
        showAlert(AlertType.ERROR, Lang.CATEGORY_NAME_ERROR, '#alert02');
        return;
    }

    if (!validateIconFA(icon)) {
        showAlert(AlertType.ERROR, Lang.ICON_FA_ERROR, '#alert02');
        return;
    }

    $.ajax({
        url: "/systemAdmin/categoryEdit",
        method: "POST",
        data: {
            id: selCategory,
            name: name,
            icon: icon
        },
        success: function (data) {
            try {

                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.CATEGORY_FORM_EDIT_SUCCESS, '#alert02');
                    location.reload();
                } else
                    showAlert(AlertType.ERROR, data.msg, '#alert02');

            } catch (e) {
                showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_EDIT_ERROR, '#alert02');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_EDIT_ERROR, '#alert02');
        }
    });
}

function category_btn_remove() {
    if (selCategory <= 0) {
        showAlert(AlertType.SUCCESS, Lang.CATEGORY_CHOOSE, '#alert03');
        return;
    }

    $.ajax({
        url: "/systemAdmin/categoryRemove",
        method: "POST",
        data: {
            id: selCategory
        },
        success: function (data) {
            try {
                if (data.success == true) {
                    showAlert(AlertType.SUCCESS, Lang.CATEGORY_FORM_REMOVE_SUCCESS, '#alert03');
                    location.reload();
                } else
                    showAlert(AlertType.ERROR, data.msg, '#alert03');

            } catch (e) {
                showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_REMOVE_ERROR, '#alert03');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.CATEGORY_FORM_REMOVE_ERROR, '#alert03');
        }
    });
}

function category_btn_changeOrder() {
    if (selCategory < 0) {
        showAlert(AlertType.SUCCESS, Lang.CATEGORY_CHOOSE, '#alert03');
        return;
    }

    $("#modalChangeList > div > div > div.modal-body").html("");

    if (selCategory == 0) {
        $("#modalChangeList > div > div > div.modal-header > h4").html("Kolejność głównych działów");

        let list = "";

        for (ob in categoriesTree) {
            list += '<li class="list-group-item" data-id="' + categoriesTree[ob].id + '"><i class="fas fa-arrows-alt-v"></i> ' + categoriesTree[ob].name + "</li>";
        }

        $("#modalChangeList > div > div > div.modal-body").html('<ul class="list-group" id="modalCatList">' + list + "</ul>");

        new Sortable(modalCatList, {
            animation: 150,
            ghostClass: 'blue-background-class'
        });

    } else {
        let obj = category_searchTreeByID(selCategory);

        $("#modalChangeList > div > div > div.modal-header > h4").html("Kolejność w dziale " + obj.name);

        let list = "";

        for (ob in obj.subcategories) {
            list += '<li class="list-group-item" data-id="' + obj.subcategories[ob].id + '"><i class="fas fa-arrows-alt-v"></i> ' + obj.subcategories[ob].name + "</li>";
        }

        $("#modalChangeList > div > div > div.modal-body").html('<ul class="list-group" id="modalCatList">' + list + "</ul>");

        new Sortable(modalCatList, {
            animation: 150,
            ghostClass: 'blue-background-class'
        });
    }

    $("#modalChangeList").modal();
}

function category_btn_listSave() {
    let index = 1;
    let newIDs = [];

    $("#modalChangeList > div > div > div.modal-body > ul").find('li').each(function () {
        let el = $(this);
        newIDs[el.attr("data-id")] = index;
        index++;
    });

    $.ajax({
        url: "/systemAdmin/categoryChangeOrder",
        method: "POST",
        data: {
            newids: toObject(newIDs)
        },
        success: function (data) {
            try {
                if (data.success == true)
                    location.reload();
            } catch (e) {}
        }
    });
}

function category_load() {

    $.ajax({
        url: "/systemAdmin/categoryList",
        method: "POST",
        success: function (data) {
            try {
                if (data.success == true) {
                    for (idx in data.list1) {
                        let obj = data.list1[idx];

                        if (obj.overcategory == null || obj.overcategory == 0) {
                            categoriesTree[obj.order] = {
                                name: obj.name,
                                id: obj.id,
                                icon: obj.icon,
                                subcategories: []
                            };
                        } else {
                            let oc = category_searchTreeByID(obj.overcategory);

                            if (oc == null)
                                continue;

                            oc.subcategories[obj.order] = {
                                name: obj.name,
                                id: obj.id,
                                icon: obj.icon,
                                subcategories: []
                            };
                        }


                    }

                    category_showTree();
                    category_bindTree();

                    categoriesList[0] = "#";
                    for (idx in data.list1) {
                        let obj = data.list1[idx];

                        categoriesList[obj.id] = obj.name;
                    }

                    category_loadForm();
                } else
                    showAlert(AlertType.ERROR, Lang.CATEGORY_MAIN_LOADING_ERROR, '#alertMain');

            } catch (e) {
                showAlert(AlertType.ERROR, Lang.CATEGORY_MAIN_LOADING_ERROR, '#alertMain');
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.CATEGORY_MAIN_LOADING_ERROR, '#alertMain');
        }
    });
}

function category_loadForm() {
    let data = "";

    for (idx in categoriesList) {
        data += '<option value="' + idx + '">' + categoriesList[idx] + '</option>';
    }

    $("#fmAddList").html(data);
}

function category_showTree() {
    let data = "";

    for (idx in categoriesTree) {
        let c = categoriesTree[idx];

        data += "<li>";

        data += category_generateSubcategory(c, c['subcategories']);

        data += "</li>";
    }

    $("#categoriesList").html(data);
}

function category_generateSubcategory(cat, c) {
    if (typeof c === "undefined" || c.length == 0)
        return '<span data-categoryID="' + cat.id + '"><i class="fas ' + cat.icon + '"></i> ' + cat.name + "</span>";
    else {
        let data = "";
        data += '<span data-categoryID="' + cat.id + '"><i class="fas ' + cat.icon + '"></i> ' + cat.name + '</span><span class="ml-1" data-toggle="collapse" data-target="#tree_sub' + cat.id + '"><i class="fas fa-arrow-down"></i></span>';
        data += '<ul><div id="tree_sub' + cat.id + '" class="collapse">';

        for (sub in c) {
            if (typeof c[sub]['subcategories'] === "undefined" || c[sub]['subcategories'].length == 0)
                data += '<li><span data-categoryID="' + c[sub].id + '"><i class="fas ' + c[sub].icon + '"></i> ' + c[sub].name + "</span></li>";
            else {
                data += "<li>" + category_generateSubcategory(c[sub], c[sub]['subcategories']) + "</li>";
            }
        }

        data += "</div></ul>";
        return data;
    }
}

function category_bindTree() {
    let els = document.querySelectorAll("[data-categoryID]");

    for (let i = 0; i < els.length; i++) {
        els[i].addEventListener('click', function () {
            let id = els[i].getAttribute("data-categoryID");

            $("[data-categoryID='" + selCategory + "']").removeClass("tree-item-selected");

            selCategory = id;

            if (id != 0) {
                let obj = category_searchTreeByID(id);
                $("#fmEditName").val(obj.name);
                $("#fmEditIcon").val(obj.icon);

                $("#_currentCatID").html(obj.id);
                $("#_currentCatName").html(obj.name);

            } else {
                $("#fmEditName").val("");
                $("#fmEditIcon").val("");
            }
            $("[data-categoryID='" + id + "']").addClass("tree-item-selected");


        });
    }
}

function category_searchTreeByID(id) {

    for (ob in categoriesTree) {
        if (categoriesTree[ob].id == id)
            return categoriesTree[ob];

        let o = category_searchCateByID(categoriesTree[ob], id);

        if (o != null)
            return o;
    }

    return null;

}

function category_searchCateByID(obj, id) {
    if (obj.subcategories == null || obj.subcategories.length <= 0)
        return null;

    for (ix in obj.subcategories) {

        let o = obj.subcategories[ix];

        if (o.id == id)
            return o;

        let oo = category_searchCateByID(o, id);

        if (oo != null)
            return oo;

    }

    return null;
}