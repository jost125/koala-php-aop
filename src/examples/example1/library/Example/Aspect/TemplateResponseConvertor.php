<?php

namespace Example\Aspect;

use Example\Response\FileTemplateResponse;
use Koala\AOP\Aspect;
use Koala\AOP\Around;
use Koala\AOP\Joinpoint;

/**
 * @Aspect
 */
class TemplateResponseConvertor {

	private $templateDir;

	public function __construct($templateDir) {
		$this->templateDir = $templateDir;
	}

	/**
	 * @Around("execution(public \Example\Controller*::*Action(..))")
	 */
	public function convert(Joinpoint $joinpoint) {
		$result = $joinpoint->proceed();
		$action = $joinpoint->getMethodName();
		$templateName = substr($action, 0, -6);
		return new FileTemplateResponse($this->templateDir . '/' . $joinpoint->getClassShortName() . '/' . $templateName . '.html', $result);
	}
}
