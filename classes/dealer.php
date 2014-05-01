<?php

class Dealer
{
	public $deck;
	public $cards = array();
	
	public function __construct($deck)
	{
		if (empty($deck)) {
			return false;
		}
		$this->deck = $deck;
		$this->getCards();
	}
	
	public function setDeck($deck)
	{
		$this->deck = $deck;
	}
	
	public function getCards()
	{
		if (empty($this->deck)) {
			return false;
		}
		foreach ($this->deck as $card) {
			$card = getCardInfo($card);
			if (false !== $card && Game::CARD_STATUS_DEALER == $card['status']) {
				$this->cards[] = array(
					'value' => $card['value'],
					'lear' => $card['lear'],
				);
			}
		}
		return $this->cards;
	}
}

?>
