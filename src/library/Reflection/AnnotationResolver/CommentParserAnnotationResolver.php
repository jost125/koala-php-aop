<?php

namespace Reflection\AnnotationResolver;

class CommentParserAnnotationResolver implements \Reflection\AnnotationResolver {

	private $annotationExpressionMatcher;

	function __construct(\Reflection\AnnotationExpressionMatcher $annotationExpressionMatcher) {
		$this->annotationExpressionMatcher = $annotationExpressionMatcher;
	}

	/**
	 * @param string $className
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation($className, \Reflection\AnnotationExpression $annotationExpression) {
		$classReflection = new \ReflectionClass($className);
		$comment = $classReflection->getDocComment();
		$matches = $this->annotationExpressionMatcher->match($annotationExpression, $comment);
		return $matches === null ? false : !empty($matches);
	}

	/**
	 * @param string $className
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @return \ReflectionMethod[]
	 */
	public function getMethodsHavingAnnotation($className, \Reflection\AnnotationExpression $annotationExpression) {
		$classReflection = new \ReflectionClass($className);
		$allMethods = $classReflection->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);
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
}
