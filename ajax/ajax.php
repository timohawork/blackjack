<?php

session_start();

if (empty($_SESSION) || empty($_POST) || !isset($_POST['request'])) {
	die();
}

include_once '../functions.php';

$player = new Player($_SESSION['name']);
$game = new Game($player);
$result = array('result' => null);

switch ($_POST['request']) {
	case 'status':
		$result['result'] = $game->getStatus();
	break;

	case 'move':
		$result['result'] = $game->move();
	break;
}

echo json_encode($result);

?>
