$(document).ready(function() {
	$('#doBet').live('click', function() {
		window.location.href = 'index.php?do=bet&bet='+$('#betValue').val();
		return false;
	});
});