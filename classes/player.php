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
		$player = $this->getPlayerData();
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
		$player = $this->getPlayerData();
		if (null !== $player) {
			$this->money = $player['money'];
		}
		else {
			$sql = "INSERT INTO players (name, money) VALUES ('".$this->name."', ".self::START_MONEY.")";
			$this->_db->query($sql);
			$player = $this->getPlayerData();
			$this->money = self::START_MONEY;
		}
		$_SESSION['id'] = $this->id = $player['id'];
		$_SESSION['name'] = $this->name;
	}
	
	public function logout()
	{
		if (empty($_SESSION)) {
			return false;
		}
		session_destroy();
		unset($_SESSION);
		return true;
	}
	
	protected function getPlayerData()
	{
		return $this->select(array(
			'select' => array('id', 'name', 'money'),
			'from' => 'players',
			'where' => array(array(
				'name' => 'name',
				'operator' => '=',
				'value' => $this->name
			))
		));
	}
}

?>
