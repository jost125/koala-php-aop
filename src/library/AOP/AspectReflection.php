<?php

namespace AOP;

use ReflectionClass;

interface AspectReflection {
	/**
	 * @param ReflectionClass $reflectionClass
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect(ReflectionClass $reflectionClass);
}
