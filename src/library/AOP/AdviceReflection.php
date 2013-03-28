<?php

namespace AOP;

use AOP\Abstraction\Advice;
use ReflectionClass;

interface AdviceReflection {

	/**
	 * @param ReflectionClass $aspect
	 * @return Advice[]
	 */
	public function getAdvices(ReflectionClass $aspect);
}
