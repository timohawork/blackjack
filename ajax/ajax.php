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

	case 'bet':
		if (empty($_POST['bet'])) {
			return false;
		}
		$game->bet = $_POST['bet'];
		$game->move_code = Game::MOVE_CODE_NEW_DECK;
		$result['result'] = $game->save(array('bet', 'move_code'));
	break;
	
	case 'getCard':
		$game->move_code = Game::MOVE_CODE_PLAYER_TAKES;
		$result['result'] = $game->save(array('move_code'));
	break;

	case 'enough':
		$game->move_code = Game::MOVE_CODE_WAIT_FOR_DEALER_RESPONSE;
		$result['result'] = $game->save(array('move_code'));
	break;
}

echo json_encode($result);

?>
