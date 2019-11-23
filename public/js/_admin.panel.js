window.onload = function () {
    loadData();
}


function loadData() {
    $.ajax({
        url: "/systemAdmin/dashboardData",
        method: "POST",
        success: function (data) {
            if (data.success == true) {

            	let totalData = data.total;
            	$("#_totalEarningsAll").html(totalData.earningsAll + " " + cfg_currency);
            	$("#_totalEarningsMonth").html(totalData.earningsMonth + " " + cfg_currency);
            	$("#_totalProducts").html(totalData.products);
            	$("#_totalReports").html(totalData.reports);


            }
        },
        error: function () {}
    });
}