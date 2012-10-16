<?php

spl_autoload_register(function($className) {

	$dirs = array('.', 'tmp/cache/proxy');

	$fileName = preg_replace('~\\\\~', '/', $className) . '.php';
	foreach ($dirs as $dir) {
		if (file_exists($dir . '/' . $fileName)) {
			require_once $dir . '/' . $fileName;
		}
	}
});

require_once 'tmp/cache/dicontainer/DIContainer.php';

$diContainer = new DIContainer();