var products = [];

window.onload = function () {

	stickFooter();

    products.push({
        id: 1,
        name: "Chleb",
        price: 3.50,
        amount: 2,
    });

    products.push({
        id: 2,
        name: "Bułka",
        price: 0.99,
        amount: 5,
    });

    products.push({
        id: 3,
        name: "Rogalik",
        price: 2.49,
        amount: 3,
    });

    products.push({
        id: 4,
        name: "Ciasto",
        price: 16.99,
        amount: 2,
    });

    loadProducts();
    updateSummary();

}

function displayList() {
    let list = "";

    products.forEach(function (item, index) {
        list += String.raw `
							<tr class="row" data-id="` + item.id + `">
								<td class="col-5 col-sm-6 col-md-7 col-lg-6">
									<a href=""><h5>` + item.name + `</h5></a>
								</td>
								<td class="col-3 col-sm-2 col-md-2 col-lg-2">
									<h5>`+ item.price +` zł</h5>
								</td>
								<td class="col-2 col-sm-2 col-md-1 col-lg-2">
									<input type="number" class="form-control form-control-sm text-center" value="` + item.amount + `" data-product-amount="">
								</td>
								<td class="col-2 col-sm-2 col-md-2 col-lg-2">
									<h5 data-price-final="">` + rePrice(parseInt(item.amount) * parseFloat(item.price)) + ` zł</h5>
								</td>
								
							</tr>
							`;
    });

    $("#productsList").html(list);

    bindElementsInList();
}

function bindElementsInList() {

    $("[data-product-amount]").each(function (index) {
        $(this).change(function () {
            let newAmount = parseInt($(this).val());

            let id = parseInt($(this).parent().parent().attr('data-id'));

            let product = getProductByID(id);

            if(product === null)
            	return;

            product.amount = newAmount;

            let newPrice = rePrice(parseInt(newAmount) * parseFloat(product.price));

            $(this).parent().parent().find('[data-price-final]').html(newPrice + " zł");

            updateSummary();
        })
    });

}

function loadProducts() {
    displayList();
}

function updateSummary() {
	let sumPrice = 0;
	products.forEach(function (item, index) {
		sumPrice += rePrice(parseInt(item.amount) * parseFloat(item.price));
	});
	sumPrice = rePrice(sumPrice);

	$("#summaryPrice").html(sumPrice + " zł");
}

function getProductByID(id) {
    let result = null;

    products.forEach(function (item, index) {
        if (item.id == id)
            result = item;
    });

    return result;
}

function rePrice(price) {
	return Math.round(price * 100) / 100;
}