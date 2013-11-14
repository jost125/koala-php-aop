<?php

namespace Koala\Reflection\Annotation\Parsing;

use Koala\Reflection\Annotation\Annotation;
use ReflectionClass;
use ReflectionMethod;

interface AnnotationResolver {
	/**
	 * @param ReflectionClass $reflectionClass
	 * @param AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation(ReflectionClass $reflectionClass, AnnotationExpression $annotationExpression);

	/**
	 * @param ReflectionMethod $reflectionMethod
	 * @param AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasMethodAnnotation(ReflectionMethod $reflectionMethod, AnnotationExpression $annotationExpression);

	/**
	 * @param ReflectionClass $reflectionClass
	 * @param AnnotationExpression $annotationExpression
	 * @return ReflectionMethod[]
	 */
	public function getMethodsHavingAnnotation(ReflectionClass $reflectionClass, AnnotationExpression $annotationExpression);

	/**
	 * @param ReflectionMethod $reflectionMethod
	 * @param AnnotationExpression $annotationExpression
	 * @return Annotation[]
	 */
	public function getMethodAnnotations(ReflectionMethod $reflectionMethod, $annotationExpression);
}
