<?php

namespace AOP\Abstraction\Pointcut;

use AOP\Abstraction\Advice;
use AOP\Abstraction\Aspect;
use AOP\Abstraction\InterceptingMethod;
use AOP\Abstraction\Pointcut;
use AOP\Pointcut\Parser\Lexer;
use IO\Stream\StringStream;
use AOP\Pointcut\PointcutExpression;
use AOP\TestCase;
use Exception;
use ReflectionClass;
use ReflectionMethod;

class LexerTest extends TestCase {

	/**
	 * @dataProvider validExpressions
	 */
	public function testParse($expression) {
		$lexer = new Lexer(new StringStream($expression));
		$lexer->buildTree();
	}

	/**
	 * @dataProvider invalidExpressions
	 */
	public function testParse_invalid($expression, $exMessage) {
		try {
			$lexer = new Lexer(new StringStream($expression));
			$lexer->buildTree();
			$this->fail('Exception expected');
		} catch (Exception $ex) {
			$this->assertEquals($ex->getMessage(), $exMessage);
		}
	}

	public function validExpressions() {
		return array(
			array('execution(* *::*(..))'),
			array('execution(private \\Some\\NamespacedClass::withMethod())'),
			array('execution(private \\NotNamespaced::withMethod())'),
			array('execution(private *EndsWith::withMethod())'),
			array('execution(private \\BeginsWith*::withMethod())'),
			array('execution(private \\BeginsWith*EndsWith::withMethod())'),
			array('execution(private *\\ClassInAnyDepth::withMethod())'),
			array('execution(private \\Some\\NamespacedClass::withMethod(..))'),
			array('execution(private \\Some\\NamespacedClass::withMethod(var, var))'),
			array('execution(private \\Some\\NamespacedClass::withMethod($captured1, var, \\Some\\Param $captured2))'),
			array('execution(* *::*(..)) and execution(* *::*(..))'),
			array('execution(* *::*(..)) and (execution(* *::*(..)) or execution(* *::*(..)))'),
			array('execution(* *::*(..)) and (
					execution(* *::*(..)) or
					execution(* *::*(..))
				)'),
		);
	}

	public function invalidExpressions() {
		return array(
			array('execation(* *::*(..))', 'Unexpected char \'a\' at position 5'),
			array('execution(privates \\Some\\NamespacedClass::withMethod())', 'Unexpected char \'s\' at position 18'),
			array('execution(private NotNamespaced::withMethod())', 'Unexpected char \'N\' at position 19'),
			array('execution(private *EndsWith::withMethod()', 'Unexpected EOF at position 41'),
			array('execution(private \\BeginsWith*:withMethod())', 'Unexpected char \'w\' at position 32'),
			array('execution(private \\::withMethod())', 'Unexpected char \':\' at position 20'),
			array('execution(privat *\\ClassInAnyDepth::withMethod())', 'Unexpected char \' \' at position 17'),
			array('execution(private withMethod(..))', 'Unexpected char \'w\' at position 19'),
			array('execution(\\Some\\NamespacedClass::withMethod(var, var))', 'Unexpected char \'\\\' at position 11'),
			array('execution(private \\Some\\NamespacedClass::withMethod($captured1, var, \\Param ..))', 'Unexpected char \'.\' at position 77'),
			array('execution()', 'Unexpected char \')\' at position 11'),
			array('execution(* *::*(..)) and (execution(* *::*(..)) or execution(* *::*(..))) a', 'Unexpected char \' \' at position 74'),
			array('a execution(* *::*(..)) and (execution(* *::*(..)) or execution(* *::*(..)))', 'Unexpected char \'a\' at position 0'),
		);
	}

}
