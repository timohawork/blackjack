<?php

class Dealer
{
	public $deck;
	public $cards = array();
	public $points;
	
	public function __construct($deck)
	{
		if (empty($deck)) {
			return false;
		}
		$this->deck = $deck;
		$this->getCards();
		$this->getPoints();
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
	
	public function getPoints()
	{
		if (empty($this->cards)) {
			return 0;
		}
		$this->points = 0;
		foreach ($this->cards as $card) {
			$this->points += cardPoint($card['value']);
		}
		return $this->points;
	}
}

?>
