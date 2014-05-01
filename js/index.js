$(document).ready(function() {
	$('#doBet').live('click', function() {
		window.location.href = 'index.php?do=bet&bet='+$('#betValue').val();
		return false;
	});
	
	startGame();
	
	function startGame()
	{
		console.log('погнали!');
		var status = getStatus();
		
		refreshTable('dealer');
		refreshTable('player');
		refreshTable('info');
		$('#status-block').text(statusDesc(status));
		
		if (status.autoMove && makeMove()) {
			setTimeout(function(){startGame()}, 1000);
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
			selector = $('#cards-block');
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
	
	return status;
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