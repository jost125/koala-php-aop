<?php

namespace Koala\AutoLoad;

use Koala\Collection\ArrayList;

class PSR0AutoLoader {

	private $dirs;

	public function __construct(ArrayList $dirs) {
		$this->dirs = $dirs;
	}

	public function register() {
		$this->dirs->each(function ($dir) {
			spl_autoload_register(function($className) use ($dir) {
				if (preg_match('~^[\\a-zA-Z0-9]+$~', $className)) {
					$fileName = preg_replace('~\\\\~', '/', $className) . '.php';
					$filePath = $dir . $fileName;
					if (file_exists($filePath)) {
						require_once $filePath;
					}
				}
			});
		});
	}

}
