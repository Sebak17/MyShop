var baners = [];

window.onload = function () {
    loadBaners();

    $('tr[data-href]').on('click', function () {
        window.location = $(this).data("href");
    });
}

function loadBaners() {
    baners.push("/img/baners/baner1.png");
    baners.push("/img/baners/baner2.png");


    for (let i = 0; i < baners.length; i++) {
        let btn = document.createElement("button");
        btn.setAttribute("type", "button");
        btn.classList.add("btn");
        btn.classList.add("btn-secondary");
        btn.classList.add("btn-sm");

        btn.addEventListener('click', function () {
            $("#main-baner > img").attr("src", baners[i]);
        });

        $("#baner-btns").append(btn);
    }
}