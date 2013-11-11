<?php

namespace IO\Storage;

class FileStorage {

	private $directory;
	private $extension;

	public function __construct($directory, $extension) {
		$this->directory = $directory;
		$this->extension = $extension;
	}

	public function put($file, $value) {
		$fileName = $this->getFileName($file);
		$dirName = substr($fileName, 0, strrpos($fileName, '/'));
		if (!is_dir($dirName)) mkdir($dirName, 0777, true);
		file_put_contents($fileName, $value);
	}

	public function get($file) {
		return file_get_contents($this->getFileName($file));
	}

	public function exists($file) {
		return file_exists($this->getFileName($file));
	}

	private function getFileName($key) {
		return rtrim($this->directory, '\\/') . '/' . $key . '.' . $this->extension;
	}
}
