$(document).ready(function() {
	$('#doBet').live('click', function() {
		window.location.href = 'index.php?do=bet&bet='+$('#betValue').val();
		return false;
	});
	
	refreshTable('dealer');
	refreshTable('player');
	refreshTable('info');
	
	refreshStatus();
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

function refreshStatus()
{
	ajaxSender.ajax({
		url: '/ajax/ajax.php',
		type: "POST",
		async: false,
		data: {request: 'status'},
		success: function(response) {
			$('#status-block').text(statusDesc($.parseJSON(response)));
		}
	});
}