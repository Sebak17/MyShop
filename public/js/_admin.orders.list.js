$(document).ready(function() {
	bindButtons();
});

function bindButtons() {
	
	$("#btnOrderSearch").click(function() {
		
		let val = parseInt($("#inp_orderID").val());

		if(isNaN(val))
			return;

		location.href = "/admin/zamowienia/informacje/" + val;

	});

}