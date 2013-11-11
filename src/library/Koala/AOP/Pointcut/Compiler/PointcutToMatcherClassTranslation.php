<?php

namespace Koala\AOP\Pointcut\Compiler;

use Koala\AOP\Pointcut\PointcutExpression;
use Koala\Cache\ICache;

class PointcutToMatcherClassTranslation {

	private $cache;

	public function __construct(ICache $cache) {
		$this->cache = $cache;
	}

	public function translate(PointcutExpression $pointcutExpression) {
		if (!$this->cache->exists($pointcutExpression->getExpression())) {
			$className = $this->generateUniqueMatcherClassName();
			$this->cache->put($pointcutExpression->getExpression(), $className);
		}
		return $this->cache->get($pointcutExpression->getExpression());
	}

	private function generateUniqueMatcherClassName() {
		do {
			$className = 'MethodMatcher' . md5(rand());
		} while ($this->cache->exists($className));
		return $className;
	}

}