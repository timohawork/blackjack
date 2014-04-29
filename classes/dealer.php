<?php

class Dealer
{
	public $deck;
	public $cards = array();
	
	public function __construct($deck)
	{
		$this->deck = $deck;
		foreach ($this->deck as $card) {
			$card = getCardInfo($card);
			if (false !== $card && Game::CARD_STATUS_DEALER == $card['status']) {
				$this->cards[] = array(
					'value' => $card['value'],
					'lear' => $card['lear'],
				);
			}
		}
	}
}

?>
