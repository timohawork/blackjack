<?php

session_start();

if (empty($_SESSION) || empty($_POST) || !isset($_POST['request'])) {
	die();
}

include_once '../functions.php';

$player = new Player($_SESSION['name']);
$game = new Game($player);
$html = '';

switch ($_POST['request']) {
	case 'dealer':
		$html = '<h1>Карты дилера</h1>'.(!empty($game->dealer->cards) ? handHtml($game->dealer->cards) : '');
	break;

	case 'player':
		$playerData = $game->playerData();
		$html = '<h1>Выши карты</h1>'.handHtml($playerData['cards']).'<span class="points">'.$playerData['points'].'</span>';
	break;

	case 'info':
		$html = '<span>Баланс: '.$player->money.'$</span>
			<span>';
		if (!empty($game->bet)) {
			$html .= 'Ставка: '.$game->bet.'$</span>
			<span>';
		}
		switch ($game->move_code) {
			case Game::MOVE_CODE_WAITING_FOR_BET:
				$html .= '<a id="doBet" href="#">Поставить</a> <select id="betValue" name="betValue">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="5">5</option>
					<option value="10">10</option>
					<option value="25">25</option>
					<option value="50">50</option>
					<option value="100">100</option>
				</select>$';
			break;
		}
		$html .= '</span>
			<span><a href="index.php?do=logout">Выйти</a></span>';
	break;
}

echo $html;

?>
