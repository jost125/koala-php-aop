<?php

namespace AOP\AspectReflection;

use ReflectionClass;

class SimpleAspectReflection implements \AOP\AspectReflection {

	private $adviceReflection;

	public function __construct(\AOP\AdviceReflection $adviceReflection) {
		$this->adviceReflection = $adviceReflection;
	}

	/**
	 * @param ReflectionClass $aspectReflection
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect(ReflectionClass $aspectReflection) {
		$advices = $this->adviceReflection->getAdvices($aspectReflection);
		$aspect = new \AOP\Abstraction\Aspect($advices);

		return $aspect;
	}

}
