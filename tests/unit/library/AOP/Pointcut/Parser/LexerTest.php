<?php

namespace AOP\Abstraction\Pointcut;

use Exception;
use Koala\AOP\Abstraction\Pointcut;
use Koala\AOP\Pointcut\Parser\Lexer;
use Koala\AOP\TestCase;
use Koala\IO\Stream\StringInputStream;

class LexerTest extends TestCase {

	/**
	 * @dataProvider validExpressions
	 */
	public function testParse($expression) {
		$lexer = new Lexer(new StringInputStream($expression));
		$lexer->buildTree();
	}

	/**
	 * @dataProvider invalidExpressions
	 */
	public function testParse_invalid($expression, $exMessage) {
		try {
			$lexer = new Lexer(new StringInputStream($expression));
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
			array('methodAnnotated(\\Some\\MethodAnnotation) and execution(* *::*(..))'),
			array('classAnnotated(\\Some\\ClassAnnotation) and execution(* *::*(..))'),
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
			array('methodAnotated(*)', 'Unexpected char \'o\' at position 9'),
			array('methodAnnotated(*)', 'Unexpected char \'*\' at position 17'),
			array('methodAnnotated(*AnnotationEndsWith)', 'Unexpected char \'*\' at position 17'),
			array('methodAnnotated(\\BeginsWith*AnnotationEndsWith)', 'Unexpected char \'*\' at position 28'),
			array('a execution(* *::*(..)) and (execution(* *::*(..)) or execution(* *::*(..)))', 'Unexpected char \'a\' at position 0'),
		);
	}

}
