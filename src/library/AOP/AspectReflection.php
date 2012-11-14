<?php

namespace AOP;

interface AspectReflection {
	/**
	 * @param string $className
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect($className);
}
