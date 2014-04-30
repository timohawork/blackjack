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
	
	const MOVE_CODE_NEW_GAME = 0;
	const MOVE_CODE_WAITING_FOR_BET = 1;
	const MOVE_CODE_NEW_DECK = 2;
	const MOVE_CODE_DEALER = 3;
	const MOVE_CODE_PLAYER = 4;
	const MOVE_CODE_WAIT_FOR_RESPONSE = 5;

	public $player;
	public $dealer;
	public $deck;
	public $move_code;
	public $bet;
	
	public function __construct($player)
	{
		if ($player instanceof Player) {
			parent::connect();
			$this->player = $player;
			$this->getVals(array('deck', 'move_code', 'bet'));
			$this->dealer = new Dealer($this->deck);
		}
	}
	
	public function move()
	{
		switch ($this->getStatus()) {
			case self::MOVE_CODE_NEW_GAME:
				$this->move_code = self::MOVE_CODE_WAITING_FOR_BET;
				$this->save(array('move_code'));
			break;
		
			case self::MOVE_CODE_WAITING_FOR_BET:
				
			break;
		
			case self::MOVE_CODE_NEW_DECK:
				if (null !== $this->bet) {
					$this->move_code = self::MOVE_CODE_DEALER;
					$this->newDeck()->save(array('deck', 'move_code'));
				}
			break;
		
			
		}
	}
	
	public function save($attributes = array())
	{
		if (empty($this->player)) {
			return false;
		}
		$query = $this->select(array(
			'select' => array('id'),
			'from' => 'games',
			'where' => array(array(
				'name' => 'player_id',
				'operator' => '=',
				'value' => $this->player->id
			))
		));
		$values = array();
		if (empty($attributes)) {
			if (!empty($this->deck)) {
				$values['cards'] = null !== $this->deck ? serialize($this->deck) : null;
			}
			if (!empty($this->move_code)) {
				$values['move_code'] = $this->move_code;
			}
			if (!empty($this->bet)) {
				$values['bet'] = $this->bet;
			}
		}
		else if (is_array($attributes)) {
			foreach ($attributes as $attribute) {
				if (isset($this->{$attribute})) {
					'deck' === $attribute ? $values['cards'] = serialize($this->deck) : $values[$attribute] = $this->{$attribute};
				}
			}
		}
		if (!empty($query)) {
			return $this->update(array(
				'table' => 'games',
				'values' => $values,
				'where' => array(array(
					'name' => 'player_id',
					'operator' => '=',
					'value' => $this->player->id
				))
			));
		}
		$values['player_id'] = $this->player->id;
		return 1 < count($values) ? $this->insert(array(
			'table' => 'games',
			'values' => $values
		)) : false;
	}
	
	public function getVals($attributes)
	{
		foreach ($attributes as $i => $attribute) {
			if (!in_array($attribute, array('deck', 'move_code', 'bet'))) {
				return null;
			}
			else if ('deck' === $attribute) {
				$attributes[$i] = 'cards';
			}
		}
		$query = $this->select(array(
			'select' => $attributes,
			'from' => 'games',
			'where' => array(array(
				'name' => 'player_id',
				'operator' => '=',
				'value' => $this->player->id
			))
		));
		if (!empty($query)) {
			$rows = array();
			foreach ($query as $name => $value) {
				if ('cards' === $name) {
					$this->deck = unserialize($value);
					$rows['deck'] = $this->deck;
				}
				else {
					$this->{$name} = $value;
					$rows[$name] = $this->{$name};
				}
			}
			return $rows;
		}
		return null;
	}
	
	public function newDeck()
	{
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
			if (0 == $num || 1 == $num) {
				$card['status'] = self::CARD_STATUS_PLAYER;
				$deckMass[$num] = collectCardCode($card);
			}
			else if (2 == $num || 3 == $num) {
				$card['status'] = self::CARD_STATUS_DEALER;
				$deckMass[$num] = collectCardCode($card);
			}
		}
		$this->deck = $deckMass;
		return $this;
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
	
	public function getStatus()
	{
		if (empty($this->move_code)) {
			return self::MOVE_CODE_NEW_GAME;
		}
		return $this->move_code;
	}
}

?>
