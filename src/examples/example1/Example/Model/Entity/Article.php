<?php

namespace Example\Model\Entity;

class Article {
	private $name;
	private $text;

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function getText() {
		return $this->text;
	}
}
