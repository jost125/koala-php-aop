<?php

namespace Reflection\AnnotationResolver;

use Doctrine\Common\Annotations\AnnotationReader;
use Reflection\AnnotationExpressionMatcher;
use Reflection\Annotation\DoctrineWrappedAnnotation;
use ReflectionClass;
use ReflectionMethod;
use Reflection\Annotation;
use Reflection\AnnotationExpression;
use Reflection\AnnotationResolver;

class DoctrineAnnotationResolver implements AnnotationResolver {

	private $annotationReader;
	private $annotationExpressionMatcher;

	public function __construct(AnnotationReader $annotationReader, AnnotationExpressionMatcher $annotationExpressionMatcher) {
		$this->annotationReader = $annotationReader;
		$this->annotationExpressionMatcher = $annotationExpressionMatcher;
	}

	/**
	 * @param ReflectionClass $reflectionClass
	 * @param AnnotationExpression $annotationExpression
	 * @return boolean
	 */
	public function hasClassAnnotation(ReflectionClass $reflectionClass, AnnotationExpression $annotationExpression) {
		$classAnnotations = $this->annotationReader->getClassAnnotations($reflectionClass);
		foreach ($classAnnotations as $classAnnotation) {
			if ($this->annotationExpressionMatcher->match($annotationExpression, new DoctrineWrappedAnnotation($classAnnotation))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param ReflectionClass $reflectionClass
	 * @param AnnotationExpression $annotationExpression
	 * @return ReflectionMethod[]
	 */
	public function getMethodsHavingAnnotation(ReflectionClass $reflectionClass, AnnotationExpression $annotationExpression) {
		$methods = $reflectionClass->getMethods();
		$filteredMethods = array();
		foreach ($methods as $method) {
			$annotations = $this->annotationReader->getMethodAnnotations($method);
			foreach ($annotations as $annotation) {
				if ($this->annotationExpressionMatcher->match($annotationExpression, new DoctrineWrappedAnnotation($annotation))) {
					$filteredMethods[] = $method;
				}
			}
		}

		return $filteredMethods;
	}

	/**
	 * @param ReflectionMethod $reflectionMethod
	 * @param AnnotationExpression $annotationExpression
	 * @return Annotation[]
	 */
	public function getMethodAnnotations(ReflectionMethod $reflectionMethod, $annotationExpression) {
		$annotations = $this->annotationReader->getMethodAnnotations($reflectionMethod);
		$filteredAnnotations = array();
		foreach ($annotations as $annotation) {
			$wrappedAnnotation = new DoctrineWrappedAnnotation($annotation);
			if ($this->annotationExpressionMatcher->match($annotationExpression, $wrappedAnnotation)) {
				$filteredAnnotations[] = $wrappedAnnotation;
			}
		}

		return $filteredAnnotations;
	}
}
