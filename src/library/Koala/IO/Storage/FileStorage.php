<?php

namespace Koala\IO\Storage;

class FileStorage {

	private $directory;

	public function __construct($directory) {
		$this->directory = $directory;
	}

	public function put($fileName, $value) {
		$fileName = $this->getFilePath($fileName);
		$dirName = substr($fileName, 0, strrpos($fileName, '/'));
		if (!is_dir($dirName)) mkdir($dirName, 0777, true);
		file_put_contents($fileName, $value);
	}

	public function get($fileName) {
		return file_get_contents($this->getFilePath($fileName));
	}

	public function exists($fileName) {
		return file_exists($this->getFilePath($fileName));
	}

	private function getFilePath($fileName) {
		return rtrim($this->directory, '\\/') . '/' . $fileName;
	}

	public function includeOnce($fileName) {
		include_once $this->getFilePath($fileName);
	}
}
