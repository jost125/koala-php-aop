<?php

namespace Reflection;

interface AnnotationResolver {
	/**
	 * @param \ReflectionClass $reflectionClass
	 * @param AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation(\ReflectionClass $reflectionClass, AnnotationExpression $annotationExpression);
}
