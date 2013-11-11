<?php

namespace Collection;

use Countable;
use IteratorAggregate;

interface IMap extends Countable, IteratorAggregate {

	public function getKeys();
	public function getValues();
	public function getValue($key);
	public function isEmpty();
	public function exists($key);

	public function each(callable $map);
	/**
	 * @param callable $filterCallback
	 * @return static
	 */
	public function filter(callable $filterCallback);
	public function firstValue();
	public function firstKey();
	public function findInValues(callable $matchCallback);
	public function findInKeys(callable $matchCallback);

}
