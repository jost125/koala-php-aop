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
}
