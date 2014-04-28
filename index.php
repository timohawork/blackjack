<?php
	$db = new mysqli("localhost", "root", "123456", "blackjack");
	session_start();
	
	if (!empty($_POST)) {
		$player = $db->query("SELECT `id`, `name`, `money` FROM `players` WHERE `name` = '".$_POST['name']."'")->fetch_assoc();
		if (null !== $player) {
			$_SESSION = $player;
		}
	}
	
	/* массив с колодой карт:
	 * каждый элемент массива (карта) - массив с достоинством и мастью
	 * масти:
	 * 1 - бубна
	 * 2 - креста
	 * 3 - пика
	 * 4 - креста
	 */
	$deckMass = array();
	for ($j = 1; $j < 5; $j++) {
		for ($i = 1; $i < 14; $i++) {
			$deckMass[] = $i.'*'.$j;
		}
	}
	shuffle($deckMass);
	
	function getCardInfo($card) {
		$info = explode("*", $card);
		return array(
			'value' => $info[0],
			'lear' => $info[1]
		);
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
				<div id="dealer-block"></div>
				<div id="player-block">
					<div id="cards-block"></div>
					<div id="player-info"></div>
				</div>
			<?php endif; ?>
		</div>
	</body>
</html>