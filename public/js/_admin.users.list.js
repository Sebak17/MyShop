$(document).ready(function() {
	bindButtons();
});

function bindButtons() {
	
	$("#btnSearch1").click(function() {
		
		let email = $("#inp_email").val();

		if(!validateEmail(email))
			return;

		location.href = "/admin/uzytkownik/?email=" + email;

	});

	$("#btnSearch2").click(function() {
		
		let val = parseInt($("#inp_id").val());

		if(isNaN(val))
			return;

		location.href = "/admin/uzytkownik/?id=" + val;

	});

}