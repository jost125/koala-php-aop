<?php

namespace Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface IList extends IteratorAggregate, ArrayAccess, Countable {

	public function isEmpty();
	public function put($value);

	/** @return static */
	public function flatten();

	/** @return static */
	public function filter(callable $filterCallback);
	public function find(callable $findCallback);

	public function exists(callable $existsCallback);
	public function allMatchCondition(callable $matchCallback);

	/** @return static */
	public function sort(callable $comparsionCallback);
	public function toArray();
	public function each(callable $eachCallback);
	public function first();

	/** @return static */
	public function removeNulls();

}
