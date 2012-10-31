<?php

namespace Reflection\AnnotationExpressionMatcher;

class PregAnnotationExpressionMatcher implements \Reflection\AnnotationExpressionMatcher {

	/**
	 * @param \Reflection\AnnotationExpression $annotationExpression
	 * @param string $comment
	 * @return string
	 */
	public function match(\Reflection\AnnotationExpression $annotationExpression, $comment) {
		$regexp = $this->expandExpression($annotationExpression);
		$matched = preg_match_all($regexp, $comment, $matches);
		return $matched ? $matches[1] : null;
	}

	private function expandExpression(\Reflection\AnnotationExpression $annotationExpression) {
		$escapedAnnotationExpression = preg_quote($annotationExpression->getExpression(), '~');
		return '~^\s*?\*\s*?\@(' . $escapedAnnotationExpression . ')(?![^\s])~m'; //
	}
}
