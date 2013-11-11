<?php

namespace Koala\AOP\Advice;

use Koala\AOP\Abstraction\Advice;
use ReflectionClass;

interface AdviceReflection {

	/**
	 * @param ReflectionClass $aspect
	 * @return Advice[]
	 */
	public function getAdvices(ReflectionClass $aspect);
}
