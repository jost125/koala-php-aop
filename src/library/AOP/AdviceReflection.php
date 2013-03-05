<?php

namespace AOP;

use ReflectionClass;

interface AdviceReflection {

	/**
	 * @param ReflectionClass $aspect
	 * @return \AOP\Abstraction\Advice[]
	 */
	public function getAdvices(ReflectionClass $aspect);
}
