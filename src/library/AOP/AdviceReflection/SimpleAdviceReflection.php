<?php

namespace AOP\AdviceReflection;

use AOP\Abstraction\InterceptingMethod;
use AOP\Pointcut\PointcutExpression;
use Reflection\Annotation;
use InvalidArgumentException;
use AOP\Abstraction\Pointcut\AroundPointcut;
use AOP\Abstraction\Pointcut\AfterThrowingPointcut;
use AOP\Abstraction\Pointcut\AfterReturningPointcut;
use AOP\Abstraction\Pointcut\AfterPointcut;
use AOP\Abstraction\Pointcut\BeforePointcut;
use Reflection\AnnotationExpression;
use AOP\Abstraction\Advice;
use AOP\Abstraction\Pointcut;
use ReflectionClass;

class SimpleAdviceReflection implements \AOP\AdviceReflection {

	private $annotationResolver;

	public function __construct(\Reflection\AnnotationResolver $annotationResolver) {
		$this->annotationResolver = $annotationResolver;
	}

	/**
	 * @param ReflectionClass $aspect
	 * @return \AOP\Abstraction\Advice[]
	 */
	public function getAdvices(ReflectionClass $aspect) {
		$advices = array();
		$expressions = array('\AOP\Before(..)', '\AOP\After(..)', '\AOP\AfterReturning(..)', '\AOP\AfterThrowing(..)', '\AOP\Around(..)');
		foreach ($expressions as $expression) {
			$annotationExpression = new AnnotationExpression($expression);
			$methods = $this->annotationResolver->getMethodsHavingAnnotation($aspect, $annotationExpression);

			if (count($methods)) {
				foreach ($methods as $method) {
					$annotations = $this->annotationResolver->getMethodAnnotations($method, $annotationExpression);
					foreach ($annotations as $annotation) {
						$advices[] = new Advice($this->createPoincutExpression($annotation), new InterceptingMethod($method));
					}
				}
			}
		}

		return $advices;
	}

	public function createPoincutExpression(Annotation $annotation) {
		$pointcutExpression = new PointcutExpression($annotation->getParameter('value'));
		switch ($annotation->getName()) {
			case 'AOP\Before':
				$pointcut = new BeforePointcut($pointcutExpression);
				break;
			case 'AOP\After':
				$pointcut = new AfterPointcut($pointcutExpression);
				break;
			case 'AOP\AfterReturning':
				$pointcut = new AfterReturningPointcut($pointcutExpression);
				break;
			case 'AOP\AfterThrowing':
				$pointcut = new AfterThrowingPointcut($pointcutExpression);
				break;
			case 'AOP\Around':
				$pointcut = new AroundPointcut($pointcutExpression);
				break;
			default:
				throw new InvalidArgumentException();
		}
		return $pointcut;
	}
}
