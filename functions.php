<?php

function getCardInfo($card) {
	$info = explode("*", $card);
	return 3 == count($info) ? array(
		'value' => $info[0],
		'lear' => $info[1],
		'status' => $info[2]
	) : false;
}

function cardCode($card) {
	$code = '';
	switch ($card['value']) {
		case 1:
			$code .= 'Туз';
		break;
	
		case 2:
			$code .= '2 ';
		break;
	
		case 3:
			$code .= '3 ';
		break;
	
		case 4:
			$code .= '4 ';
		break;
	
		case 5:
			$code .= '5 ';
		break;
	
		case 6:
			$code .= '6 ';
		break;
	
		case 7:
			$code .= '7 ';
		break;
	
		case 8:
			$code .= '8 ';
		break;
	
		case 9:
			$code .= '9 ';
		break;
	
		case 10:
			$code .= '10';
		break;
	
		case 11:
			$code .= '11';
		break;
	
		case 12:
			$code .= '12';
		break;
	
		case 13:
			$code .= '13';
		break;
	}
	switch ($card['lear']) {
		case Game::CODE_DIAMONDS:
			$code .= ' Бубей';
		break;
	
		case Game::CODE_CLUBS:
			$code .= ' Треф';
		break;
	
		case Game::CODE_SPADES:
			$code .= ' Пик';
		break;
	
		case Game::CODE_HEARTS:
			$code .= ' Червей';
		break;
	}
	return $code;
}
?>
