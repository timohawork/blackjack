<?php

include_once dirname(__FILE__).'/config.php';
include_once dirname(__FILE__).'/classes/player.php';
include_once dirname(__FILE__).'/classes/game.php';
include_once dirname(__FILE__).'/classes/dealer.php';

function getCardInfo($card) {
	$info = explode("*", $card);
	return 3 == count($info) ? array(
		'value' => $info[0],
		'lear' => $info[1],
		'status' => $info[2]
	) : false;
}

function collectCardCode($card)
{
	if (!is_array($card)) {
		return false;
	}
	return $card['value'].'*'.$card['lear'].'*'.$card['status'];
}

function cardPoint($card)
{
	if (11 <= $card) {
		return 10;
	}
	return $card;
}

function cardHtml($card, $postiton)
{
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
			$value = $card['value'];
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
	return '<div class="card-block pos'.$postiton.'">
		<span>'.$value.'</span>
		<div class="lear '.$lear.'">&nbsp;</div>
	</div>';
}

function handHtml($cards)
{
	if (empty($cards)) {
		return '';
	}
	$html = '';
	$postiton = 1;
	foreach ($cards as $card) {
		$html .= cardHtml($card, $postiton);
		$postiton++;
	}
	return $html;
}

?>
