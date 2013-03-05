<?php

namespace Reflection\Annotation;

use AOP\TestCase;
use Doctrine\Common\Annotations\Annotation;
use Reflection\Annotation\DoctrineWrappedAnnotation;

class DoctrineWrappedAnnotationTest extends TestCase {

	public function testGetName() {
		$annotation = new DoctrineWrappedAnnotation(new FooAnnotation(array()));
		$this->assertEquals('Reflection\Annotation\FooAnnotation', $annotation->getName());
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
		$this->assertEquals('Reflection\Annotation\FooAnnotation(value="foo")', $annotation->toExpression());
	}

	public function testToExpression_complicated() {
		$annotation = new DoctrineWrappedAnnotation(new BarAnnotation(array('value' => 'foo', 'baz' => 'I have " inside')));
		$this->assertEquals('Reflection\Annotation\BarAnnotation(baz="I have \" inside", value="foo")', $annotation->toExpression());
	}

}

class FooAnnotation extends Annotation {
}

class BarAnnotation extends Annotation {
	public $baz;
}
