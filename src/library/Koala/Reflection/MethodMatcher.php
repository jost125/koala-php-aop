<?php

namespace Koala\Reflection;

use ReflectionMethod;

interface MethodMatcher {

	public function match(ReflectionMethod $reflectionMethod);

}
