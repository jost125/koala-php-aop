<?php

namespace IO\Stream;

use AOP\Pointcut\Parser\AlreadyRecordingException;
use AOP\Pointcut\Parser\NoRecordingStartedException;
use IO\Stream\InputStream;
use IO\Stream;

class StringInputStream implements Stream\InputStream {

	private $recording;
	private $record;
	private $string;
	private $pointer;
	private $length;

	public function __construct($string) {
		$this->string = $string;
		$this->pointer = 0;
		$this->length = strlen($string);
		$this->record = "";
		$this->recording = false;
	}

	public function peek() {
		if (!$this->isInside()) {
			return Stream\InputStream::EOF;
		}
		return $this->string[$this->pointer];
	}

	public function read() {
		if (!$this->isInside()) {
			return Stream\InputStream::EOF;
		}
		$c = $this->string[$this->pointer++];
		$this->record .= $c;
		return $c;
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

	public function startRecording() {
		if ($this->recording) {
			throw new AlreadyRecordingException();
		}
		$this->recording = true;
		$this->record = "";
	}

	public function stopRecording() {
		if (!$this->recording) {
			throw new NoRecordingStartedException();
		}
		$this->recording = false;
	}

	public function getRecord() {
		return $this->record;
	}
}
