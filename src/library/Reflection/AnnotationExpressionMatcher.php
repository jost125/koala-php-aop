<?php

namespace Reflection;

interface AnnotationExpressionMatcher {
	/**
	 * @param AnnotationExpression $annotationExpression
	 * @param Annotation $annotation
	 * @return string
	 */
	public function match(AnnotationExpression $annotationExpression, Annotation $annotation);
}
