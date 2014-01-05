<?php

namespace Koala\AOP;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class TestCase extends PHPUnit_Framework_TestCase {

	public function createMock($className) {
		return $this->getMockBuilder($className)
			->disableOriginalConstructor()
			->getMock();
	}

	public function getAccesibleMethod($object, $method) {
		$rc = new ReflectionClass($object);
		$rm = $rc->getMethod($method);
		$rm->setAccessible(true);

		return $rm;
	}

}
