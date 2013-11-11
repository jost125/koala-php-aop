<?php

namespace Koala\AOP\Abstraction;

class Aspect {

	private $advices;

	/**
	 * @param Advice[] $advices
	 */
	public function __construct(array $advices) {
		$this->advices = $advices;
	}

	/**
	 * @return Advice[]
	 */
	public function getAdvices() {
		return $this->advices;
	}
}
