<?php

class Game extends DB
{
	const CARD_STATUS_OPEN = 1;
	const CARD_STATUS_DEALER = 2;
	const CARD_STATUS_PLAYER = 3;
	const CARD_STATUS_REBOUND = 4;

	const CODE_DIAMONDS = 1; // бубна
	const CODE_CLUBS = 2; // креста
	const CODE_SPADES = 3; // пика
	const CODE_HEARTS = 4; // черва
	
	const MOVE_CODE_NEW_GAME = 0;
	const MOVE_CODE_WAITING_FOR_BET = 1;
	const MOVE_CODE_NEW_DECK = 2;
	const MOVE_CODE_DEALER_TAKES = 3;
	const MOVE_CODE_PLAYER_TAKES = 4;
	const MOVE_CODE_WAIT_FOR_PLAYER_RESPONSE = 5;
	
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

		$this->deck = $deckMass;
		return $this;
	}
	
	public function playerData()
	{
		if (empty($this->deck)) {
			return false;
		}
		$result = array(
			'cards' => array(),
			'points' => 0
		);
		foreach ($this->deck as $card) {
			$card = getCardInfo($card);
			if (false !== $card && self::CARD_STATUS_PLAYER == $card['status']) {
				$result['cards'][] = array(
					'value' => $card['value'],
					'lear' => $card['lear'],
				);
				$result['points'] += cardPoint($card);
			}
		}
		return $result;
	}
	
	public function move()
	{
		$status = $this->getStatus();
		$nextCard = $this->getNextCard();
		$playerData = $this->playerData();
		
		switch ($status['code']) {
			case self::MOVE_CODE_NEW_GAME:
				$this->move_code = self::MOVE_CODE_WAITING_FOR_BET;
				return $this->save(array('move_code'));
			break;

			case self::MOVE_CODE_NEW_DECK:
				if (null !== $this->bet) {
					$this->move_code = self::MOVE_CODE_DEALER_TAKES;
					return $this->newDeck()->save(array('deck', 'move_code'));
				}
			break;

			case self::MOVE_CODE_DEALER_TAKES:
				if (null === $nextCard) {
					return false;
				}

				if (2 > count($this->dealer->cards)) {
					$nextCard['status'] = self::CARD_STATUS_DEALER;
					
					if (1 == count($this->dealer->cards) && !count($playerData['cards'])) {
						$this->move_code = self::MOVE_CODE_PLAYER_TAKES;
						$this->save(array('move_code'));
					}
					return $this->saveCard($nextCard);
				}
			break;
			
			case self::MOVE_CODE_PLAYER_TAKES:
				if (null === $nextCard) {
					return false;
				}
				
				$nextCard['status'] = self::CARD_STATUS_PLAYER;
				$this->saveCard($nextCard);

				if (0 < count($playerData['cards'])) {
					$this->move_code = self::MOVE_CODE_WAIT_FOR_PLAYER_RESPONSE;
					return $this->save(array('move_code'));
				}
				return true;
			break;
			
			case self::MOVE_CODE_WAIT_FOR_PLAYER_RESPONSE:
				
			break;
		}
	}
	
	public function getNextCard()
	{
		if (empty($this->deck)) {
			return null;
		}
		$card = null;
		foreach ($this->deck as $pos => $card) {
			$card = getCardInfo($card);
			if (self::CARD_STATUS_OPEN == $card['status']) {
				$card['pos'] = $pos;
				return $card;
			}
		}
	}
	
	public function saveCard($card)
	{
		if (empty($card)) {
			return false;
		}
		$this->deck[$card['pos']] = collectCardCode($card);
		return $this->save(array('deck'));
	}
	
	public function getStatus()
	{
		if (empty($this->move_code)) {
			return array(
				'code' => self::MOVE_CODE_NEW_GAME,
				'autoMove' => true
			);
		}
		return array(
			'code' => $this->move_code,
			'autoMove' => self::iSautoMoveCode($this->move_code)
		);
	}
	
	protected static function iSautoMoveCode($code)
	{
		return in_array($code, array(
			self::MOVE_CODE_NEW_GAME,
			self::MOVE_CODE_NEW_DECK,
			self::MOVE_CODE_DEALER_TAKES,
			self::MOVE_CODE_PLAYER_TAKES
		));
	}
}

?>
