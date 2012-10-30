<?php

spl_autoload_register(function($className) {
	if (preg_match('~^[\\a-zA-Z0-9]+$~', $className)) {
		$fileName = preg_replace('~\\\\~', '/', $className) . '.php';
		$filePath = __DIR__ . '/library/' . $fileName;
		if (file_exists($filePath)) {
			require_once $filePath;
		}
	}
});

require_once 'tmp/cache/dicontainer/DIContainer.php';

$diContainer = new DIContainer();