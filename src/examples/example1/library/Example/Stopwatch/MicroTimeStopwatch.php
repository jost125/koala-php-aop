<?php

namespace Example\Stopwatch;

class MicroTimeStopwatch implements \Example\Stopwatch {

	private $start;

	public function start() {
		$this->start = microtime(true);
	}

	public function stop() {
		if ($this->start === null) {
			throw new \Exception('Not started');
		}

		$stop = microtime(true) - $this->start;
		$this->start = null;
		return $stop;
	}
}
