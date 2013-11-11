<?php

spl_autoload_register(function($className) {
	if (preg_match('~^[\\a-zA-Z0-9]+$~', $className)) {
		$fileName = preg_replace('~\\\\~', '/', $className) . '.php';
		$filePath = __DIR__ . '/' . $fileName;
		if (file_exists($filePath)) {
			require_once $filePath;
		}
	}
});
