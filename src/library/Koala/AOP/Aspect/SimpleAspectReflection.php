<?php

namespace Koala\AOP\Aspect;

use Koala\AOP\Abstraction\Aspect;
use Koala\AOP\Advice\AdviceReflection;
use Koala\AOP\Aspect\AspectReflection;
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
