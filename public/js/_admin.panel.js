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
            	$("#_totalEarningsAll").html(totalData.earningsAll + " zł");
            	$("#_totalEarningsMonth").html(totalData.earningsMonth + " zł");
            	$("#_totalProducts").html(totalData.products);
            	$("#_totalReports").html(totalData.reports);


            }
        },
        error: function () {}
    });
}