<?php

namespace CommentParserAnnotationResolverTest;

/**
 * @\AOP\Aspect
 */
class Baz {

	/**
	 * @\AOP\Before("execution(public *(..))")
	 */
	public function beforeAdvice() {

	}

	/**
	 * @\AOP\After("execution(public *(..))")
	 */
	public function afterAdvice() {

	}

}
