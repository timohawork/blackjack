<?php

session_start();

if (empty($_SESSION) || empty($_POST) || !isset($_POST['request'])) {
	die();
}

include_once '../functions.php';

$player = new Player($_SESSION['name']);
$game = new Game($player);
$result = '';

switch ($_POST['request']) {
	case 'status':
		$result = $game->getStatus();
	break;

	case 'move':
		$result = $game->nextMove();
	break;
}

if (!is_array($result)) {
	$result = array('result' => $result);
}

echo json_encode($result);

?>
