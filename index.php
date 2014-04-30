<?php

session_start();

include_once 'functions.php';
include_once './classes/player.php';
include_once './classes/game.php';
include_once './classes/dealer.php';

if (!empty($_SESSION)) {
	$player = new Player($_SESSION['name']);
	$game = new Game($player);
	$game->start();
	if (isset($_GET['do'])) {
		switch ($_GET['do']) {
			case 'logout':
				$player->logout() && header("Location: index.php");
			break;
		
			case 'bet':
				if (isset($_GET['bet']) && Game::MOVE_CODE_WAITING_FOR_BET == $game->getStatus()) {
					$game->bet = $_GET['bet'];
					$game->move_code = Game::MOVE_CODE_NEW_DECK;
					$game->save(array('bet', 'move_code'));
					header("Location: index.php");
				}
			break;
		}
	}
}
else if (!empty($_POST)) {
	$player = new Player($_POST['name']);
	$player->login();
}

?>
<html>
	<head>
		<title>BlackJack Online</title>
		<link rel="stylesheet" type="text/css" href="/css/main.css" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="/js/index.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<?php if (empty($_SESSION)) : ?>
				<div id="login-block">
					<h1>Пожалуйста, войдите:</h1>
					<form method="POST">
						<input type="text" name="name">
						<input type="submit" value="Войти">
					</form>
				</div>
			<?php else : ?>
				<div id="dealer-block">
					<h1>Карты дилера</h1>
					<?php if (!empty($game->dealer->cards)) : ?>
						<?=handHtml($game->dealer->cards)?>
					<?php endif; ?>
				</div>
				<div id="player-block">
					<div id="cards-block">
						<h1>Выши карты</h1>
						<?=handHtml($game->getPlayerCards())?>
					</div>
					<div id="player-info">
						<span>Баланс: <?=$player->money?>$</span>
						<span>
							<?php
								switch ($game->move_code) {
									case Game::MOVE_CODE_WAITING_FOR_BET:
										echo '<a id="doBet" href="#">Поставить</a> <select id="betValue" name="betValue">
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
							?>
						</span>
						<span><a href="index.php?do=logout">Выйти</a></span>
					</div>
				</div>
				<div id="debug_block">
					<h1>DEBUG:</h1>
					<h2>Vars:</h2>
					<div class="block">
						<h3>Game:</h3>
						<table>
							<tr>
								<td>Deck:</td>
								<td><?=var_dump($game->deck)?></td>
							</tr>
							<tr>
								<td>Move_code:</td>
								<td><?=var_dump($game->move_code)?></td>
							</tr>
							<tr>
								<td>Bet:</td>
								<td><?=var_dump($game->bet)?></td>
							</tr>
						</table>
					</div>
					<div class="block">
						<h3>Player:</h3>
						<table>
							<tr>
								<td>Money:</td>
								<td><?=var_dump($player->money)?></td>
							</tr>
						</table>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</body>
</html>