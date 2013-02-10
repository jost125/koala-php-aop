<?php

namespace AOP\AspectReflection;

use ReflectionClass;

class SimpleAspectReflection implements \AOP\AspectReflection {

	private $adviceReflection;

	public function __construct(\AOP\AdviceReflection $adviceReflection) {
		$this->adviceReflection = $adviceReflection;
	}

	/**
	 * @param ReflectionClass $reflectionClass
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect(ReflectionClass $reflectionClass) {
		$advices = $this->adviceReflection->getAdvices($reflectionClass);
		$aspect = new \AOP\Abstraction\Aspect($advices);

		return $aspect;
	}

}
