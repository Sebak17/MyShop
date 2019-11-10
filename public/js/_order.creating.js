var _currentPayment = 0;

$(document).ready(function () {

    bindPayments();

});

//
// 1 - PayU
// 2 - PayPal
// 3 - Payment Card
//

function bindPayments() {

    $("[data-payment]").each(function (index) {
        $(this).click(function () {
            let method = $(this).attr('data-payment');
            changePayment(this, method);
        });
    });

}

function changePayment(element, method) {
	method = parseInt(method);

	$("[data-payment='" + _currentPayment + "']").removeClass('card-pick');

    _currentPayment = method;

    switch (method) {
        case 1:
            $(element).addClass('card-pick');
            break;
        case 2:
            $(element).addClass('card-pick');
            break;
        case 3:
            $(element).addClass('card-pick');
            break;
    }
}