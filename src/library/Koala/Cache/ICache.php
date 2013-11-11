<?php

namespace Cache;

interface ICache {

	public function exists($key);
	public function put($key, $value);
	public function get($key);
}
