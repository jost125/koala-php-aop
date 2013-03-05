<?php

namespace AOP;

use ReflectionClass;

interface AspectReflection {
	/**
	 * @param ReflectionClass $aspectReflection
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect(ReflectionClass $aspectReflection);
}
