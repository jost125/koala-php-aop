<?php

namespace Koala\Reflection\Annotation;

use Koala\AOP\TestCase;

class DoctrineWrappedAnnotationTest extends TestCase {

	public function testGetName() {
		$annotation = new DoctrineWrappedAnnotation(new FooAnnotation(array()));
		$this->assertEquals(FooAnnotation::class, $annotation->getName());
	}

	public function testHasParameters() {
		$annotation = new DoctrineWrappedAnnotation(new FooAnnotation(array()));
		$this->assertFalse($annotation->hasParameters());
	}

	public function testHasParameters_notEmpty() {
		$annotation = new DoctrineWrappedAnnotation(new FooAnnotation(array('value' => 'foo')));
		$this->assertTrue($annotation->hasParameters());
	}

	public function testGetParameters() {
		$annotation = new DoctrineWrappedAnnotation(new FooAnnotation(array('value' => 'foo')));
		$this->assertEquals(array('value' => 'foo'), $annotation->getParameters());
	}

	public function testToExpression() {
		$annotation = new DoctrineWrappedAnnotation(new FooAnnotation(array('value' => 'foo')));
		$this->assertEquals('Koala\Reflection\Annotation\FooAnnotation(value="foo")', $annotation->toExpression());
	}

	public function testToExpression_complicated() {
		$annotation = new DoctrineWrappedAnnotation(new BarAnnotation(array('value' => 'foo', 'baz' => 'I have " inside')));
		$this->assertEquals('Koala\Reflection\Annotation\BarAnnotation(baz="I have \" inside", value="foo")', $annotation->toExpression());
	}

	public function testGetParameter() {
		$annotation = new DoctrineWrappedAnnotation(new BarAnnotation(array('value' => 'foo', 'baz' => 'I have " inside')));
		$this->assertEquals('foo', $annotation->getParameter('value'));
		$this->assertEquals('I have " inside', $annotation->getParameter('baz'));
		try {
			$annotation->getParameter('nonexisting');
			$this->fail('Expected exception');
		} catch (ParameterNotDefinedException $ex) {
			$this->assertEquals('Property "nonexisting" is not defined in Koala\Reflection\Annotation\BarAnnotation annotation', $ex->getMessage());
		}
	}

}

class FooAnnotation extends \Doctrine\Common\Annotations\Annotation {
}

class BarAnnotation extends \Doctrine\Common\Annotations\Annotation {
	public $baz;
}
