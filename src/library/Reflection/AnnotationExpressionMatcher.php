<?php

namespace Reflection;

interface AnnotationExpressionMatcher {
	/**
	 * @param AnnotationExpression $annotationExpression
	 * @param string $comment
	 * @return string
	 */
	public function match(AnnotationExpression $annotationExpression, $comment);
}
