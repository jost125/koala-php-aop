<?php

namespace Reflection\AnnotationResolver;

class CommentParserAnnotationResolver implements \Reflection\AnnotationResolver {

	private $annotationExpressionMatcher;

	function __construct(\Reflection\AnnotationExpressionMatcher $annotationExpressionMatcher) {
		$this->annotationExpressionMatcher = $annotationExpressionMatcher;
	}

	/**
	 * @param \ReflectionClass $reflectionClass
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation(\ReflectionClass $reflectionClass, \Reflection\AnnotationExpression $annotationExpression) {
		$comment = $reflectionClass->getDocComment();
		$matches = $this->annotationExpressionMatcher->match($annotationExpression, $comment);
		return $matches === null ? false : !empty($matches);
	}
}
