<?php

namespace Koala\AOP\Advice;

use InvalidArgumentException;
use Koala\AOP\Abstraction\Advice;
use Koala\AOP\Abstraction\InterceptingMethod;
use Koala\AOP\Abstraction\Pointcut\AfterPointcut;
use Koala\AOP\Abstraction\Pointcut\AfterReturningPointcut;
use Koala\AOP\Abstraction\Pointcut\AfterThrowingPointcut;
use Koala\AOP\Abstraction\Pointcut\AroundPointcut;
use Koala\AOP\Abstraction\Pointcut\BeforePointcut;
use Koala\AOP\Abstraction\Pointcut;
use Koala\AOP\After;
use Koala\AOP\AfterReturning;
use Koala\AOP\AfterThrowing;
use Koala\AOP\Around;
use Koala\AOP\Before;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\Reflection\Annotation\Annotation;
use Koala\Reflection\Annotation\Parsing\AnnotationExpression;
use Koala\Reflection\Annotation\Parsing\AnnotationResolver;
use ReflectionClass;

class SimpleAdviceReflection implements AdviceReflection {

	private $annotationResolver;

	public function __construct(AnnotationResolver $annotationResolver) {
		$this->annotationResolver = $annotationResolver;
	}

	/**
	 * @param ReflectionClass $aspect
	 * @return Advice[]
	 */
	public function getAdvices(ReflectionClass $aspect) {
		$advices = array();
		$annotationClasses = [Before::class, After::class, AfterReturning::class, AfterThrowing::class, Around::class];
		foreach ($annotationClasses as $annotationClass) {
			$annotationExpression = new AnnotationExpression('\\' . $annotationClass . '(..)');
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

	private function createPoincutExpression(Annotation $annotation) {
		$pointcutExpression = new PointcutExpression($annotation->getParameter('value'));
		switch ($annotation->getName()) {
			case Before::class:
				$pointcut = new BeforePointcut($pointcutExpression);
				break;
			case After::class:
				$pointcut = new AfterPointcut($pointcutExpression);
				break;
			case AfterReturning::class:
				$pointcut = new AfterReturningPointcut($pointcutExpression);
				break;
			case AfterThrowing::class:
				$pointcut = new AfterThrowingPointcut($pointcutExpression);
				break;
			case Around::class:
				$pointcut = new AroundPointcut($pointcutExpression);
				break;
			default:
				throw new InvalidArgumentException('Uknown pointcut ' . $annotation->getName());
		}
		return $pointcut;
	}
}
