<?php

namespace AOP;

interface AdviceReflection {

	/**
	 * @param string $className
	 * @return \AOP\Abstraction\Advice[]
	 */
	public function getAdvices($className);
}
