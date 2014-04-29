<?php

class Game extends DB
{
	const CARD_STATUS_OPEN = 1;
	const CARD_STATUS_DEALER = 2;
	const CARD_STATUS_PLAYER = 3;

	const CODE_DIAMONDS = 1; // бубна
	const CODE_CLUBS = 2; // креста
	const CODE_SPADES = 3; // пика
	const CODE_HEARTS = 4; // черва

	public $player;
	public $dealer;
	public $deck;
	
	public function __construct($player)
	{
		if ($player instanceof Player) {
			parent::connect();
			$this->player = $player;
			$this->getDeck();
			$this->dealer = new Dealer($this->deck);
		}
	}
	
	public function start()
	{
		if (empty($this->deck)) {
			/* массив с колодой карт:
			* каждый элемент массива (карта) - массив с достоинством и мастью
			* масти:
			* 1 - бубна
			* 2 - креста
			* 3 - пика
			* 4 - черва
			*/
			$deckMass = array();
			for ($j = 1; $j < 5; $j++) {
				for ($i = 1; $i < 14; $i++) {
					$deckMass[] = $i.'*'.$j.'*'.self::CARD_STATUS_OPEN;
				}
			}
			shuffle($deckMass);
			
			foreach ($deckMass as $num => $card) {
				$card = getCardInfo($card);
				if (0 == $num || 2 == $num) {
					$card['status'] = self::CARD_STATUS_DEALER;
					$deckMass[$num] = collectCardCode($card);
				}
				elseif (1 == $num || 3 == $num) {
					$card['status'] = self::CARD_STATUS_PLAYER;
					$deckMass[$num] = collectCardCode($card);
				}
			}
			$this->deck = $deckMass;
			$this->setDeck($deckMass);
		}
		
	}
	
	public function getDeck() {
		if (empty($this->player)) {
			return false;
		}
		if (!empty($this->deck)) {
			return $this->deck;
		}
		$deck = $this->select(array(
			'select' => array('cards'),
			'from' => 'games',
			'where' => array(array(
				'name' => 'player_id',
				'operator' => '=',
				'value' => $this->player->id
			))
		));
		if (!empty($deck)) {
			$this->deck = unserialize($deck['cards']);
			return $this->deck;
		}
		return null;
	}
	
	public function setDeck($deck)
	{
		return $this->insert(array(
			'table' => 'games',
			'values' => array(
				'player_id' => $this->player->id,
				'cards' => null !== $deck ? serialize($deck) : null
			)
		));
	}
	
	public function getPlayerCards()
	{
		if (empty($this->deck)) {
			return false;
		}
		$cards = array();
		foreach ($this->deck as $card) {
			$card = getCardInfo($card);
			if (false !== $card && self::CARD_STATUS_PLAYER == $card['status']) {
				$cards[] = array(
					'value' => $card['value'],
					'lear' => $card['lear'],
				);
			}
		}
		return $cards;
	}
}

?>
