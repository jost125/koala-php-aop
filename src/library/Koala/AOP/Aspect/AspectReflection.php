<?php

namespace Koala\AOP\Aspect;

use Koala\AOP\Abstraction\Aspect;
use ReflectionClass;

interface AspectReflection {
	/**
	 * @param ReflectionClass $aspectReflection
	 * @return Aspect
	 */
	public function getAspect(ReflectionClass $aspectReflection);
}
