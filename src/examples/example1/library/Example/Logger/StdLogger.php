<?php

namespace Example\Logger;

class StdLogger implements \Example\Logger {

	public function log($message, $severity) {
		echo 'message: ' . $message . ', severity: ' . $severity . "\n";
	}
}
