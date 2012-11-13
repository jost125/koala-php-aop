<?php

namespace AOP;

interface AspectReflection {
	/**
	 * @param \DI\Definition\ServiceDefinition $aspectDefinition
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect(\DI\Definition\ServiceDefinition $aspectDefinition);
}
