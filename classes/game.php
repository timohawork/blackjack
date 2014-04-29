<?php

class Game extends DB
{
	public $player;
	public $dealer;
	public $deck;
	
	public function __construct($player)
	{
		if ($player instanceof Player) {
			parent::connect();
			$this->player = $player;
			$this->getDeck();
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
					$deckMass[] = $i.'*'.$j;
				}
			}
			shuffle($deckMass);
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
}

?>
