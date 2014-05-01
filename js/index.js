$(document).ready(function() {
	$('#doBet').live('click', function() {
		window.location.href = 'index.php?do=bet&bet='+$('#betValue').val();
		return false;
	});
	
	refreshTable('dealer');
	refreshTable('player');
	refreshTable('info');
	
	game();
	
	function game()
	{
		//console.log('обработка хода');
		var status = getStatus();
		
		refreshTable('dealer');
		refreshTable('player');
		refreshTable('info');
		$('#status-block').text(statusDesc(status));
		
		if (status.autoMove && makeMove()) {
			setTimeout(function(){game()}, 2000);
		}
	}
});

function refreshTable(block) {
	var selector;
	switch (block) {
		case 'dealer':
			selector = $('#dealer-block');
		break;
		
		case 'player':
			selector = $('#player-block');
		break;
		
		case 'info':
			selector = $('#player-info');
		break;
		
		default:
			return;
		break;
	}
	ajaxSender.ajax({
		url: '/ajax/layouts.php',
		type: "POST",
		async: false,
		data: {request: block},
		success: function(response) {
			selector.html(response);
		}
	});
}

function getStatus()
{
	var status;
	
	ajaxSender.ajax({
		url: '/ajax/ajax.php',
		type: "POST",
		async: false,
		data: {request: 'status'},
		success: function(response) {
			status = $.parseJSON(response);
		}
	});
	
	return status.result;
}

function makeMove()
{
	var request;
	
	ajaxSender.ajax({
		url: '/ajax/ajax.php',
		type: "POST",
		async: false,
		data: {request: 'move'},
		success: function(response) {
			request = $.parseJSON(response);
		}
	});
	
	return request.result;
}