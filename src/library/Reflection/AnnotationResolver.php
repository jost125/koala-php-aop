<?php

namespace Reflection;

interface AnnotationResolver {
	/**
	 * @param string $className
	 * @param AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation($className, AnnotationExpression $annotationExpression);

	/**
	 * @param string $className
	 * @param AnnotationExpression $annotationExpression
	 * @return \ReflectionMethod[]
	 */
	public function getMethodsHavingAnnotation($className, AnnotationExpression $annotationExpression);
}
