<?php

namespace AOP;

use ReflectionClass;

interface AdviceReflection {

	/**
	 * @param ReflectionClass $reflectionClass
	 * @return \AOP\Abstraction\Advice[]
	 */
	public function getAdvices(ReflectionClass $reflectionClass);
}
