<?php

session_start();

include_once 'functions.php';
include_once './classes/player.php';
include_once './classes/game.php';
include_once './classes/dealer.php';

if (!empty($_SESSION)) {
	$player = new Player($_SESSION['name']);
	if (isset($_GET['do'])) {
		'logout' === $_GET['do'] && $player->logout() && header("Location: index.php");
	}
	$game = new Game($player);
	$game->start();
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
					<?php $postiton = 1; ?>
					<?php foreach ($game->dealer->cards as $card) : ?>
						<?php 
							$value = $lear = '';
							switch ($card['value']) {
								case 1:
									$value = 'Т';
								break;
							
								case 11:
									$value = 'В';
								break;
							
								case 12:
									$value = 'Д';
								break;
							
								case 13:
									$value = 'К';
								break;
							
								default:
									$value .= $card['value'];
								break;
							}
							switch ($card['lear']) {
								case Game::CODE_DIAMONDS:
									$lear = 'diamonds';
								break;
							
								case Game::CODE_CLUBS:
									$lear = 'clubs';
								break;
							
								case Game::CODE_SPADES:
									$lear = 'spades';
								break;
							
								case Game::CODE_HEARTS:
									$lear = 'hearts';
								break;
							}
						?>
						<div class="card-block pos<?=$postiton?>">
							<span><?=$value?></span>
							<div class="lear <?=$lear?>">&nbsp;</div>
						</div>
						<?php $postiton++; ?>
					<?php endforeach; ?>
				</div>
				<div id="player-block">
					<div id="cards-block"></div>
					<div id="player-info">
						<a href="index.php?do=logout">Выйти</a>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</body>
</html>