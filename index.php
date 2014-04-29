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
					<?php foreach ($game->dealer->cards as $card) : ?>
						<div class="card-block">
							<h1><?=cardCode($card)?></h1>
						</div>
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