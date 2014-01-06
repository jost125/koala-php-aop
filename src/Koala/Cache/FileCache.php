<?php

namespace Koala\Cache;

class FileCache implements ICache {

	private $file;
	private $cache;
	private $loaded;

	public function __construct($file) {
		$this->file = $file;
		$this->cache = [];
		$this->loaded = false;
	}

	public function exists($key) {
		$this->loadCache();
		return isset($this->cache[$key]);
	}

	public function put($key, $value) {
		$this->loadCache();
		$this->cache[$key] = $value;
		$this->saveCache();
	}

	public function get($key) {
		$this->loadCache();
		return isset($this->cache[$key]) ? $this->cache[$key] : null;
	}

	private function loadCache() {
		if (!$this->loaded) {
			if (file_exists($this->file)) {
				$this->cache = unserialize(file_get_contents($this->file));
			} else {
				$this->cache = [];
			}
			$this->loaded = true;
		}
	}

	private function saveCache() {
		if (!file_exists($this->file)) {
			mkdir(dirname($this->file), 0777, true);
		}
		file_put_contents($this->file, serialize($this->cache));
	}
}
