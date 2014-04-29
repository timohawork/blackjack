<?php

function getCardInfo($card) {
	$info = explode("*", $card);
	return 3 == count($info) ? array(
		'value' => $info[0],
		'lear' => $info[1],
		'status' => $info[2]
	) : false;
}

?>
