<?php

class DB
{
	protected $_db;
	
	protected function connect()
	{
		$this->_db = new mysqli("localhost", "root", "123456", "blackjack");
	}
}

?>
