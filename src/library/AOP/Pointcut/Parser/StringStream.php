<?php

namespace AOP\Pointcut\Parser;

class StringStream implements Stream {

	private $string;
	private $pointer;
	private $length;

	public function __construct($string) {
		$this->string = $string;
		$this->pointer = 0;
		$this->length = strlen($string);
	}

	public function peek() {
		if (!$this->isInside()) {
			return Stream::EOF;
		}
		return $this->string[$this->pointer];
	}

	public function read() {
		if (!$this->isInside()) {
			return Stream::EOF;
		}
		return $this->string[$this->pointer++];
	}

	private function isInside() {
		return $this->pointer < $this->length;
	}

	public function pointer() {
		return $this->pointer;
	}

	public function readSection($start, $end) {
		return substr($this->string, $start, $end);
	}
}
