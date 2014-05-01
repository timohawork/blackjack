$(document).ready(function() {
	$('#doBet').live('click', function() {
		addBet($('#betValue').val()) && game();
		return false;
	});
	
	$('#getCard').live('click', function() {
		ajaxSender.ajax({
			url: '/ajax/ajax.php',
			type: "POST",
			async: false,
			data: {request: 'getCard'},
			success: function(response) {
				response = $.parseJSON(response);
				response.result && game();
			}
		});
		return false;
	});
	
	$('#enough').live('click', function() {
		ajaxSender.ajax({
			url: '/ajax/ajax.php',
			type: "POST",
			async: false,
			data: {request: 'enough'},
			success: function(response) {
				response = $.parseJSON(response);
				response.result && game();
			}
		});
		return false;
	});
	
	/*$('#getCard').live('click', function() {
		return false;
	});*/
	
	refreshTable('dealer');
	refreshTable('player');
	refreshTable('info');
	
	game();
	
	function game()
	{
		console.log('обработка хода');
		var status = getStatus();
		
		refreshTable('dealer');
		refreshTable('player');
		refreshTable('info');
		$('#status-block').html(statusDesc(status));
		
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

function addBet(bet)
{
	var request;
	
	ajaxSender.ajax({
		url: '/ajax/ajax.php',
		type: "POST",
		async: false,
		data: {request: 'bet', bet: bet},
		success: function(response) {
			request = $.parseJSON(response);
		}
	});
	
	return request.result;
}