<?php

session_start();

if (empty($_SESSION) || empty($_POST) || !isset($_POST['request'])) {
	die();
}

include_once '../functions.php';

$player = new Player($_SESSION['name']);
$game = new Game($player);

switch ($_POST['request']) {
	case 'status':
		echo $game->getStatus();
	break;

	case 'move':
		echo json_encode($game->move());
	break;
}

?>
