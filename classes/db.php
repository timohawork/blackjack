<?php

class DB
{
	protected $_db;
	
	protected function connect()
	{
		$this->_db = new mysqli("localhost", "root", "123456", "blackjack");
	}
	
	protected function select($params)
	{
		if (empty($params)) {
			return false;
		}
		$sql = "
			SELECT ".implode(', ', $params['select'])."
			FROM ".$params['from']."
			";
		if (!empty($params['leftJoin'])) {
			foreach ($params['leftJoin'] as $leftJoin) {
				$sql .= "LEFTJOIN ".$leftJoin['table']." ".$leftJoin['alias']."
				ON ".$leftJoin['name']." ".$leftJoin['operator']." ".$leftJoin['value'];
			}
		}
		$sql .= "
			WHERE ";
		foreach ($params['where'] as $where) {
			$sql .= $where['name'].' '.$where['operator'].' '.(is_numeric($where['value']) ? $where['value'] : "'".$where['value']."'");
		}
		return $this->_db->query($sql)->fetch_assoc();
	}
	
	protected function insert($data)
	{
		if (empty($data)) {
			return false;
		}
		$names = $values = array();
		foreach ($data['values'] as $name => $value) {
			$names[] = $name;
			$values[] = is_numeric($value) ? $value : "'".$value."'";
		}
		$sql = "INSERT INTO {$data['table']} (".implode(', ', $names).") VALUES (".implode(', ', $values).")";
		return $this->_db->query($sql);
	}
	
	protected function update($data)
	{
		if (empty($data)) {
			return false;
		}
		$values = $whereSql = array();
		foreach ($data['values'] as $name => $value) {
			$values[] = $name.' = '.(is_numeric($value) ? $value : "'".$value."'");
		}
		foreach ($data['where'] as $where) {
			$whereSql[] = $where['name'].' '.$where['operator'].' '.(is_numeric($where['value']) ? $where['value'] : "'".$where['value']."'");
		}
		$sql = "UPDATE {$data['table']} SET ".implode(', ', $values)." WHERE ".implode(' AND ', $whereSql);
		return $this->_db->query($sql);
	}
}

?>
