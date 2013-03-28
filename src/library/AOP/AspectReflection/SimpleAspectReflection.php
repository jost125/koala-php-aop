<?php

namespace AOP\AspectReflection;

use AOP\Abstraction\Aspect;
use AOP\AdviceReflection;
use AOP\AspectReflection;
use ReflectionClass;

class SimpleAspectReflection implements AspectReflection {

	private $adviceReflection;

	public function __construct(AdviceReflection $adviceReflection) {
		$this->adviceReflection = $adviceReflection;
	}

	/**
	 * @param ReflectionClass $aspectReflection
	 * @return Aspect
	 */
	public function getAspect(ReflectionClass $aspectReflection) {
		$advices = $this->adviceReflection->getAdvices($aspectReflection);
		$aspect = new Aspect($advices);

		return $aspect;
	}

}
