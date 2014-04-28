<?php

function getCardInfo($card) {
	$info = explode("*", $card);
	return array(
		'value' => $info[0],
		'lear' => $info[1]
	);
}

?>
