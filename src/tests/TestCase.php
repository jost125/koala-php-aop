<?php

namespace AOP;

use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase {

	public function createMock($className) {
		return $this->getMockBuilder($className)
			->disableOriginalConstructor()
			->getMock();
	}

}
