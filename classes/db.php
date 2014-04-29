<?php

class DB
{
	protected $_db;
	
	protected function connect()
	{
		$this->_db = new mysqli("localhost", "root", "123456", "blackjack");
	}
	
	public function getData($params)
	{
		if (empty($params)) {
			return false;
		}
		$sql = "
			SELECT ".implode(', ', $params['select'])."
			FROM ".$params['from']."
			WHERE ";
		foreach ($params['where'] as $where) {
			$sql .= $where['name'].' '.$where['operator'].' '.(is_string($where['value']) ? "'".$where['value']."'" : $where['value']);
		}
		return $this->_db->query($sql)->fetch_assoc();
	}
}

?>
