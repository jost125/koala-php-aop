<?php

namespace GeneratedAOPProxy\Example\Model\Facade;

use AOP\Joinpoint;
use ReflectionMethod;

class ArticleModelFacade extends \Example\Model\Facade\ArticleModelFacade {

	private $aspect;
	private $interceptedService;

	/**
	 * @var ReflectionMethod[]
	 */
	private $interceptingMethods;

	public function __construct($aspect, $interceptedService, array $interceptingMethods) {
		$this->aspect = $aspect;
		$this->interceptedService = $interceptedService;
		$this->interceptingMethods = $interceptingMethods;
	}

	/**
	 * @override
	 */
	public function fetchArticleById($articleId) {
		$joinpoint = new Joinpoint($this->interceptedService, new ReflectionMethod('\Example\Model\Facade\ArticleModelFacade', 'fetchArticleById'), array($articleId));

		$result = null;
		foreach ($this->interceptingMethods as $interceptingMethod) {
			$result = $interceptingMethod->invokeArgs($this->aspect, array($joinpoint));
		}

		return $result;
	}
}
