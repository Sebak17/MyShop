var _orderID = -1,
    _currentDeliver = '',
    _currentLocker = '';
var lockerMap;

$(document).ready(function () {

    _orderID = $("[data-id]").attr('data-id');

    bindButtons();

    changeHistoryList();
});

function bindButtons() {

    $('#btnShowHistory').click(function () {
        $('#modalHistory').modal('show');
    });

    $("#modalHistoryType").change(function () {
        changeHistoryList(this.value);
    });
}

function changeHistoryList(type = 'ALL') {

    let list = "";

    historyData.forEach(function (item, index) {
        
    	if(type != 'ALL' && item.type != type)
    		return;

        list += String.raw`
        <tr>
	        <td>` + item.data + `</td>
	        <td>` + item.typeName + `</td>
	        <td>` + item.ip + `</td>
	        <td>` + item.time + `</td>
        </tr>
        `;

    });

    $("#modalHistoryBox").html(list);

}