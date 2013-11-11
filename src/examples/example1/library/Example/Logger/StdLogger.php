<?php

namespace Example\Logger;

use Example\Logger;

class StdLogger implements Logger {

	public function log($message, $severity) {
		echo 'message: ' . $message . ', severity: ' . $severity . "\n";
	}
}
