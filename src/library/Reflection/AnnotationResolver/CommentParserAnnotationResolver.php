<?php

namespace Reflection\AnnotationResolver;

use ReflectionClass;
use ReflectionMethod;

class CommentParserAnnotationResolver implements \Reflection\AnnotationResolver {

	private $annotationExpressionMatcher;

	function __construct(\Reflection\AnnotationExpressionMatcher $annotationExpressionMatcher) {
		$this->annotationExpressionMatcher = $annotationExpressionMatcher;
	}

	/**
	 * @param ReflectionClass $reflectionClass
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation(ReflectionClass $reflectionClass, \Reflection\AnnotationExpression $annotationExpression) {
		$comment = $reflectionClass->getDocComment();
		$matches = $this->annotationExpressionMatcher->match($annotationExpression, $comment);
		return $matches === null ? false : !empty($matches);
	}

	/**
	 * @param ReflectionClass $reflectionClass
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @return \ReflectionMethod[]
	 */
	public function getMethodsHavingAnnotation(ReflectionClass $reflectionClass, \Reflection\AnnotationExpression $annotationExpression) {
		$allMethods = $reflectionClass->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);
		$methodsHavingAnnotation = array();
		foreach ($allMethods as $method) {
			$comment = $method->getDocComment();
			$matches = $this->annotationExpressionMatcher->match($annotationExpression, $comment);
			if ($matches) {
				$methodsHavingAnnotation[] = $method;
			}
		}

		return $methodsHavingAnnotation;
	}

	/**
	 * @param ReflectionMethod $reflectionMethod
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @return \Reflection\Annotation[]
	 */
	public function getMethodAnnotations(ReflectionMethod $reflectionMethod, $annotationExpression) {
		$comment = $reflectionMethod->getDocComment();
		$matches = $this->annotationExpressionMatcher->match($annotationExpression, $comment);


	}
}
