<?php

namespace Koala\AOP\Pointcut\Compiler;

use Koala\AOP\Pointcut\Parser\Lexer;
use Koala\AOP\TestCase;
use Koala\IO\Stream\StringInputStream;

class CompileMethodMatchVisitorTest extends TestCase {

	/**
	 * @dataProvider expressions
	 */
	public function testVisit($expression, $compiled) {
		$visitor = new CompileMethodMatchVisitor();
		$lexer = new Lexer(new StringInputStream($expression));

		$tree = $lexer->buildTree();
		$tree->acceptVisitor($visitor);

		$this->assertEquals($compiled, $visitor->getCompiled());
	}

	public function expressions() {
		return array(
			array(
				'execution(* *::*(..))',
				'(preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName()))'
			),
			array(
				'execution(protected \\Some\\NamespacedClass::withMethod())',
				'(preg_match(\'~^Some\\\\\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())',
			),
			array(
				'execution(protected \\NotNamespaced::withMethod())',
				'(preg_match(\'~^NotNamespaced$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())',
			),
			array(
				'execution(protected *EndsWith::withMethod())',
				'(preg_match(\'~^.*?EndsWith$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())',
			),
			array(
				'execution(protected \\BeginsWith*::withMethod())',
				'(preg_match(\'~^BeginsWith.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())'
			),
			array(
				'execution(protected \\BeginsWith*EndsWith::withMethod())',
				'(preg_match(\'~^BeginsWith.*?EndsWith$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())'
			),
			array(
				'execution(protected *\\ClassInAnyDepth::withMethod())',
				'(preg_match(\'~^.*?\\\\\\\\ClassInAnyDepth$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())',
			),
			array(
				'execution(protected \\Some\\NamespacedClass::withMethod(..))',
				'(preg_match(\'~^Some\\\\\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected())',
			),
			array(
				'execution(protected \\Some\\NamespacedClass::withMethod(var, var))',
				'(preg_match(\'~^Some\\\\\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected() && $reflectionMethod->getNumberOfParameters() === 2)'
			),
			array(
				'execution(protected \\Some\\NamespacedClass::withMethod($captured1, var, \\Param $captured2))',
				'(preg_match(\'~^Some\\\\\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isProtected() && $reflectionMethod->getNumberOfParameters() === 3 && $this->getMethodArgument(2)->getClass() === \'\\\\Param\')'
			),
			array(
				'execution(* *::*(..)) and execution(* *::*(..))',
				'(preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())) && (preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName()))'
			),
			array(
				'execution(* *::*(..)) and (execution(* *::*(..)) or execution(* *::*(..)))',
				'(preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())) && ((preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())) || (preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())))'
			),
			array(
				'execution(* *::*(..)) and (
					execution(* *::*(..)) or
					execution(* *::*(..))
				)',
				'(preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())) && ((preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())) || (preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName())))'
			),
			array(
				'methodAnnotated(\\Some\\Annotation)',
				'($this->annotationResolver->hasMethodAnnotation($reflectionMethod, new \Koala\Reflection\Annotation\Parsing\AnnotationExpression(\'\\Some\\Annotation\')))'
			),
			array(
				'methodAnnotated(\\Some\\Annotation) and execution(* *::*(..))',
				'($this->annotationResolver->hasMethodAnnotation($reflectionMethod, new \Koala\Reflection\Annotation\Parsing\AnnotationExpression(\'\\Some\\Annotation\'))) && (preg_match(\'~^.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^.*?$~\', $reflectionMethod->getName()))'
			),
		);
	}

}
