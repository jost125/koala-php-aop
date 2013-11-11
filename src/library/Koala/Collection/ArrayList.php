<?php

namespace Koala\Collection;

use ArrayIterator;
use Traversable;

class ArrayList implements IList {

	protected $items;

	public function __construct(array $items = []) {
		$this->items = $items;
	}

	public function getIterator() {
		return new ArrayIterator($this->items);
	}

	public function count() {
		return count($this->items);
	}

	public function isEmpty() {
		return ($this->count() === 0);
	}

	public function offsetGet($offset) {
		return $this->items[$offset];
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this->items);
	}

	/**
	 * @inheritdoc
	 */
	public function offsetSet($offset, $value) {
		$this->items[$offset] = $value;
	}

	public function put($value) {
		$this->items[] = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetUnset($offset) {
		unset($this->items[$offset]);
	}

	public function flatten() {
		return $this->doFlatten($this->items);
	}

	private function doFlatten($items) {
		$flattened = [];
		if ($items instanceof Traversable || is_array($items)) {
			foreach ($items as $item) {
				$flattened = array_merge($flattened, $this->doFlatten($item));
			}
		}
		else {
			$flattened[] = $items;
		}
		return new static($flattened);
	}

	public function filter(callable $filterCallback) {
		$filtered = array();
		foreach ($this->items as $item) {
			if ($filterCallback($item)) {
				$filtered[] = $item;
			}
		}
		return new static($filtered);
	}

	public function find(callable $findCallback) {
		foreach ($this->items as $item) {
			if ($findCallback($item)) {
				return $item;
			}
		}
		return null;
	}

	public function exists(callable $existsCallback) {
		return $this->find($existsCallback) !== null;
	}

	public function allMatchCondition(callable $matchCallback) {
		return !$this->isEmpty() && !$this->exists(function ($item) use ($matchCallback) {
			return !$matchCallback($item);
		});
	}

	public function sort(callable $comparsionCallback) {
		$copied = $this->items;
		usort($copied, $comparsionCallback);
		return new static($copied);
	}

	public function toArray() {
		return $this->items;
	}

	public function each(callable $eachCallback) {
		foreach ($this->items as $item) {
			$eachCallback($item);
		}
	}

	public function first() {
		return $this->find(function ($item) {
			return true;
		});
	}

	public function removeNulls() {
		return $this->filter(function ($item) {
			return $item !== null;
		});
	}
}
