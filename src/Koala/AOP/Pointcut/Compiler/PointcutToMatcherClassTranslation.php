<?php

namespace Koala\AOP\Pointcut\Compiler;

use Doctrine\Common\Cache\Cache;
use Koala\AOP\Pointcut\PointcutExpression;

class PointcutToMatcherClassTranslation {

	private $cache;

	public function __construct(Cache $cache) {
		$this->cache = $cache;
	}

	public function translate(PointcutExpression $pointcutExpression) {
		if (!$this->cache->contains($pointcutExpression->getExpression())) {
			$className = $this->generateUniqueMatcherClassName();
			$this->cache->save($pointcutExpression->getExpression(), $className);
		}
		return $this->cache->fetch($pointcutExpression->getExpression());
	}

	private function generateUniqueMatcherClassName() {
		do {
			$className = 'MethodMatcher' . md5(rand());
		} while ($this->cache->contains($className));
		return $className;
	}

}