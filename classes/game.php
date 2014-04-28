<?php

class Game {
	public $player_id;
	public $deck;
	
	public function start()
	{
		/* массив с колодой карт:
		* каждый элемент массива (карта) - массив с достоинством и мастью
		* масти:
		* 1 - бубна
		* 2 - креста
		* 3 - пика
		* 4 - креста
		*/
		$deckMass = array();
		for ($j = 1; $j < 5; $j++) {
			for ($i = 1; $i < 14; $i++) {
				$deckMass[] = $i.'*'.$j;
			}
		}
		shuffle($deckMass);
		$this->deck = $deckMass;
	}
}

?>
