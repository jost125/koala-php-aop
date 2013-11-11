<?php

namespace Example\Controller;

class HiController {

	private $hiMessage;

	public function setHiMessage($hiMessage) {
		$this->hiMessage = $hiMessage;
	}

	public function sayHiAction() {
		echo $this->hiMessage . "\n";
	}

}
