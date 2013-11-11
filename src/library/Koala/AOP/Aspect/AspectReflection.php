<?php

namespace AOP\Aspect;

use AOP\Abstraction\Aspect;
use ReflectionClass;

interface AspectReflection {
	/**
	 * @param ReflectionClass $aspectReflection
	 * @return Aspect
	 */
	public function getAspect(ReflectionClass $aspectReflection);
}
