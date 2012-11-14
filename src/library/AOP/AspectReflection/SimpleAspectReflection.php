<?php

namespace AOP\AspectReflection;

class SimpleAspectReflection implements \AOP\AspectReflection {

	private $adviceReflection;

	public function __construct(\AOP\AdviceReflection $adviceReflection) {
		$this->adviceReflection = $adviceReflection;
	}

	/**
	 * @param string $className
	 * @return \AOP\Abstraction\Aspect
	 */
	public function getAspect($className) {
		$advices = $this->adviceReflection->getAdvices($className);
		$aspect = new \AOP\Abstraction\Aspect($advices);

		return $aspect;
	}

}
