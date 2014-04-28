<?php

include_once 'db.php';

class Player extends DB
{
	const START_MONEY = 100;
	
	public $id;
	public $name;
	public $money;
	
	public function __construct($name)
	{
		parent::connect();
		
		$this->name = $name;
		
		if (empty($this->name)) {
			return false;
		}
		$player = $this->getData();
		if (null !== $player) {
			$_SESSION['id'] = $this->id = $player['id'];
			$_SESSION['name'] = $this->name;
			$this->money = $player['money'];
		}
	}
	
	public function login()
	{
		if (empty($this->name) || isset($_SESSION['id'])) {
			return false;
		}
		$player = $this->getData();
		if (null !== $player) {
			$this->money = $player['money'];
		}
		else {
			$sql = "INSERT INTO players (name, money) VALUES ('".$this->name."', ".self::START_MONEY.")";
			$this->_db->query($sql);
			$player = $this->getData();
			$this->money = self::START_MONEY;
		}
		$_SESSION['id'] = $this->id = $player['id'];
		$_SESSION['name'] = $this->name;
	}
	
	protected function getData()
	{
		$sql = "
			SELECT id, name, money
			FROM players
			WHERE name = '{$this->name}'
		";
		return $this->_db->query($sql)->fetch_assoc();
	}
}

?>
