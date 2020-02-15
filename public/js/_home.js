var banners = [];

$( document ).ready(function() {
    loadBaners();

    $('tr[data-href]').on('click', function () {
        window.location = $(this).data("href");
    });
});

function loadBaners() {
    for (let i = 0; i < banners.length; i++) {
        let btn = document.createElement("button");
        btn.setAttribute("type", "button");
        btn.classList.add("btn");
        btn.classList.add("btn-secondary");
        btn.classList.add("btn-sm");

        btn.addEventListener('click', function () {
            $("#main-baner > img").attr("src", banners[i]);
        });

        $("#baner-btns").append(btn);
    }
}