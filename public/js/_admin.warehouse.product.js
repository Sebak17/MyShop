var productItems = [];
var currentPage = 1;
var maxPage;
var itemsPerPage = 10;

$(document).ready(function () {
    bindButtons();

    loadItems();
});

function bindButtons() {
    $("#btnAddItemModal").click(function () {
        $("#modalItemAdd").modal('show');
    });

    $("#btnAddItem").click(function () {
        system_addItem();
    });
	
	$("#btnItemsPageNext").click(function() {
		currentPage++;
		if(currentPage > maxPage)
			currentPage = maxPage;

		$("#inp_pageNumber").val(currentPage);
		showItems();
	});

	
	$("#btnItemsPagePrev").click(function() {
		currentPage--;
		if(currentPage < 1)
			currentPage = 1;

		$("#inp_pageNumber").val(currentPage);
		showItems();
	});

	$("#inp_pageNumber").change(function (){
		let v = parseInt($("#inp_pageNumber").val());

		if(v < 1 || v > maxPage) {
			$("#inp_pageNumber").val(currentPage);
			return;
		}


		showItems();
	});
}

function bindItems() {
    $("[data-item-id]").each(function (index) {
        let fg = $(this).parent().find(".form-group");
        let st = $(this).parent().find("h5");

        let itemID = $(this).parent().find("[data-item-id]").attr('data-item-id');

        st.dblclick(function () {
            fg.removeClass('d-none');
            st.addClass('d-none');

            fg.find("select").focus();
        });

        fg.change(function () {
            fg.addClass('d-none');
            st.removeClass('d-none');

            system_updateItem(itemID, fg.find("select").val());
        });

        fg.focusout(function () {
            fg.addClass('d-none');
            st.removeClass('d-none');
        });


        let btnHistory = $(this).parent().find("[data-btn-type=history]");
        btnHistory.click(function(){
        	showItemHistory(itemID);
        });

    });
}

function loadItems() {
    let d_id = $("[data-id]").attr('data-id');

    $.ajax({
        url: "/systemAdmin/warehouseItemsList",
        method: "POST",
        data: {
            id: d_id,
        },
        success: function (data) {
            if (data.success == true) {
                let m = "";

                Object.values(data.items).forEach(function (item, key) {
                    productItems[item.id] = {
                        id: item.id,
                        code: item.code,
                        status: item.status,
                        created_at: item.created_at,
                    };
                });

                maxPage = Math.ceil(productItems.length / itemsPerPage);
                $("#inp_pageNumber").val(currentPage);
                $("#lastSiteNumber").html(maxPage);

                showItems();
            } else {}
        },
        error: function () {}
    });
}

function showItems() {
	let m = "", i = 0;

	for(let key in  productItems) {
		i++;

		if(i <= ( currentPage * itemsPerPage - itemsPerPage) || i > ( currentPage * itemsPerPage)) {
			continue;
		}


		let item = productItems[key];


		let input = "";
        for (let key in itemStatusName) {
            input += String.raw `<option value="` + key + `" ` + (key == item.status ? 'selected' :
                '') + ` >` + itemStatusName[key] + `</option>`;
        };

        m += String.raw `
			<tr>
				<td class="align-middle text-center" data-item-id="` + item.id + `">
					<h6>#` + item.id + `</h6>
				</td>
				<td class="align-middle text-left">
					<h6>` + item.code + `</h6>
				</td>
				<td>
					<h5>` + itemStatusName[item.status] + `</h5>
					<div class="form-group d-none">
						<select class="form-control">` + input + `</select>
					</div>
				</td>
				<td class="align-middle text-center">
					<div>` + item.created_at + `</div>
				</td>
				<td>
					<button class="btn btn-info btn-sm" data-btn-type="history"><i class="fas fa-history"></i></button>
				</td>
			</tr>`;
	}

    $("#itemsList").html(m);
    bindItems();
}

function showItemHistory(d_itemID) {
	let d_productID = $("[data-id]").attr('data-id');
	

	$.ajax({
        url: "/systemAdmin/warehouseHistoryItem",
        method: "POST",
        data: {
            product_id: d_productID,
            item_id: d_itemID,
        },
        success: function (data) {
            if (data.success == true) {

				if(data.history != null) {
					let m = "";

					Object.values(data.history).forEach(function (item, key) {
	                   m += String.raw`<tr><td>` + item.created_at + `</td><td>` + item.data + `</td></tr>`;
	                });
					$("#modalHistoryData").html(m);
                	$("#modalItemHistory").modal('show');
				}
				

            } else { }
        },
        error: function () {}
    });

}

function updateItemRow(id) {
    let m = $("[data-item-id=" + id + "]").parent();
    let item = productItems[id];

    if (m == null || item == null)
        return;


    let input = "";
    for (let key in itemStatusName) {
        input += String.raw `<option value="` + key + `" ` + (key == item.status ? 'selected' : '') + ` >` + itemStatusName[key] + `</option>`;
    };

    m.html(String.raw `
			<td class="align-middle text-center" data-item-id="` + item.id + `">
				<h6>#` + item.id + `</h6>
			</td>
			<td class="align-middle text-left">
				<h6>` + item.code + `</h6>
			</td>
			<td>
				<h5>` + itemStatusName[item.status] + `</h5>
				<div class="form-group d-none">
					<select class="form-control">` + input + `</select>
				</div>
			</td>
			<td class="align-middle text-center">
				<div>` + item.created_at + `</div>
			</td>
			<td>
				<button class="btn btn-info btn-sm" data-btn-type="history"><i class="fas fa-history"></i></button>
			</td>`);

    bindItems();
}

function system_addItem() {
    let d_id = $("[data-id]").attr('data-id');
    let d_code = $("#inp_addItemCode").val();
    let d_status = $("#inp_addItemStatus").val();

    if (d_code == '' || d_code.length < 6 || d_code.length > 100) {
        showAlert(AlertType.ERROR, Lang.WAREHOUSE_ITEM_CODE_ERROR, "#alert01");
        return;
    }

    if (d_status == '') {
        showAlert(AlertType.ERROR, Lang.WAREHOUSE_ITEM_STATUS_CHOOSE, "#alert01");
        return;
    }

    showAlert(AlertType.LOADING, Lang.FORM_SENDING, "#alert01");

    $.ajax({
        url: "/systemAdmin/warehouseAddItem",
        method: "POST",
        data: {
            id: d_id,
            code: d_code,
            status: d_status,
        },
        success: function (data) {
            if (data.success == true) {
                showAlert(AlertType.SUCCESS, Lang.WAREHOUSE_ITEM_ADD_SUCCESS, "#alert01");

                $("#modalItemAdd").modal('hide');
				$("#inp_addItemCode").val('');
				$("#inp_addItemStatus").val('');
				showAlert(AlertType.NONE, '', "#alert01");

                loadItems();
            } else {
                if (data.msg != null)
                    showAlert(AlertType.ERROR, data.msg, "#alert01");
                else
                    showAlert(AlertType.ERROR, Lang.WAREHOUSE_ITEM_ADD_ERROR, "#alert01");
            }
        },
        error: function () {
            showAlert(AlertType.ERROR, Lang.WAREHOUSE_ITEM_ADD_ERROR, "#alert01");
        }
    });
}

function system_updateItem(d_itemID, d_status) {
    let d_productID = $("[data-id]").attr('data-id');

    $.ajax({
        url: "/systemAdmin/warehouseUpdateItem",
        method: "POST",
        data: {
            product_id: d_productID,
            item_id: d_itemID,
            status: d_status,
        },
        success: function (data) {
            if (data.success == true) {
                let item = productItems[data.item.id];
                if (item != null) {
                    item.status = data.item.status;
                    updateItemRow(data.item.id);
                }

            } else {
                showAlertDismissible(AlertType.ERROR, Lang.WAREHOUSE_ITEM_UPDATE_ERROR);
            }
        },
        error: function () {
            showAlertDismissible(AlertType.ERROR, Lang.WAREHOUSE_ITEM_UPDATE_ERROR);
        }
    });
}