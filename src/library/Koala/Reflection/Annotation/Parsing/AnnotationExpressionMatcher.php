<?php

namespace Koala\Reflection\Annotation\Parsing;

use Koala\Reflection\Annotation\Annotation;
use Koala\Reflection\Annotation\Parsing\AnnotationExpression;

interface AnnotationExpressionMatcher {
	/**
	 * @param AnnotationExpression $annotationExpression
	 * @param Annotation $annotation
	 * @return string
	 */
	public function match(AnnotationExpression $annotationExpression, Annotation $annotation);
}
