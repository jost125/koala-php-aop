<?php

namespace Reflection\Annotation\Parsing;

use Reflection\Annotation\Annotation;
use Reflection\Annotation\Parsing\AnnotationExpression;

interface AnnotationExpressionMatcher {
	/**
	 * @param AnnotationExpression $annotationExpression
	 * @param Annotation $annotation
	 * @return string
	 */
	public function match(AnnotationExpression $annotationExpression, Annotation $annotation);
}
