<?php

namespace Koala\AOP\Pointcut\Compiler;

use Koala\AOP\Pointcut\Parser\Lexer;
use Koala\IO\Stream\StringInputStream;
use Koala\AOP\TestCase;

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
				'execution(private \\Some\\NamespacedClass::withMethod())',
				'(preg_match(\'~^\\\\Some\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())',
			),
			array(
				'execution(private \\NotNamespaced::withMethod())',
				'(preg_match(\'~^\\\\NotNamespaced$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())',
			),
			array(
				'execution(private *EndsWith::withMethod())',
				'(preg_match(\'~^.*?EndsWith$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())',
			),
			array(
				'execution(private \\BeginsWith*::withMethod())',
				'(preg_match(\'~^\\\\BeginsWith.*?$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())'
			),
			array(
				'execution(private \\BeginsWith*EndsWith::withMethod())',
				'(preg_match(\'~^\\\\BeginsWith.*?EndsWith$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())'
			),
			array(
				'execution(private *\\ClassInAnyDepth::withMethod())',
				'(preg_match(\'~^.*?\\\\ClassInAnyDepth$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())',
			),
			array(
				'execution(private \\Some\\NamespacedClass::withMethod(..))',
				'(preg_match(\'~^\\\\Some\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate())',
			),
			array(
				'execution(private \\Some\\NamespacedClass::withMethod(var, var))',
				'(preg_match(\'~^\\\\Some\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate() && $reflectionMethod->getNumberOfParameters() === 2)'
			),
			array(
				'execution(private \\Some\\NamespacedClass::withMethod($captured1, var, \\Param $captured2))',
				'(preg_match(\'~^\\\\Some\\\\NamespacedClass$~\', $reflectionMethod->getDeclaringClass()->getName()) && preg_match(\'~^withMethod$~\', $reflectionMethod->getName()) && $reflectionMethod->isPrivate() && $reflectionMethod->getNumberOfParameters() === 3 && $this->getMethodArgument(2)->getClass() === \'\\\\Param\')'
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
		);
	}

}
