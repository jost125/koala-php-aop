<?php

namespace Example\Stopwatch;

class StopwatchFactory {
	public function createStopwatch() {
		return new MicroTimeStopwatch();
	}
}
