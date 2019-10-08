var verifyToken = "",
    isVerifying = false,
    isRequest = false;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var AlertType = {
    NONE: -1,
    SUCCESS: 1,
    ERROR: 2,
    WARNING: 3,
    LOADING: 9,
};

function showAlert(type, text = '', id = '', useIcon = true) {

    if (id == '')
        id = "#alert";

    $(id).removeClass('alert-success');
    $(id).removeClass('alert-warning');
    $(id).removeClass('alert-danger');

    if (type != -1)
        $(id).removeClass('d-none');

    switch (type) {
        case -1:
            $(id).html("");
            $(id).addClass('d-none');
            break;
        case 1:
            if (useIcon)
                text = String.raw `<i class="fas fa-check"></i> ` + text;
            $(id).html(text);
            $(id).addClass('alert-success');
            break;
        case 2:
            if (useIcon)
                text = String.raw `<i class="fas fa-exclamation"></i> ` + text;
            $(id).addClass('alert-danger');
            $(id).html(text);
            break;
        case 3:
            if (useIcon)
                text = String.raw `` + text;
            $(id).html(text);
            $(id).addClass('alert-warning');
            break;

        case 9:
            $(id).html(String.raw `<i class="fas fa-circle-notch fa-spin"></i> ` + text);
            $(id).addClass('alert-warning');
            break;
    }
}

function stickFooter() {

    $("#footer").addClass("fixed-bottom");
    $("#footer").css("z-index", "-1");

}

function changeTitle(a) {
    if (a != "")
        a = " - " + a;
    $("title").html("MojeZakupy" + a);
}

function randomInt(min, max) {
    return Math.floor(Math.random() * (Math.floor(max) - Math.ceil(min) + 1)) + Math.ceil(min);
}

function randomFloat(min, max) {
    return (Math.random() * (min - max) + max);
}

function toObject(ar) {
    let o = {};
    for (let key in ar)
        o[key] = ar[key];
    return o;
}

function generateRecaptchaToken(type) {
    if(isVerifying) 
        return;

    isVerifying = true;
    grecaptcha.execute('6Le5WrgUAAAAAPNugal1jOzvPYE4kzFXhMpZxywY', {
        action: type
    }).then(function (token) {
        verifyToken = token;
        isVerifying = false;
    });
}