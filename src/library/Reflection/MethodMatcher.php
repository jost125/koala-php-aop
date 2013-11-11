<?php

namespace Reflection;

use ReflectionMethod;

interface MethodMatcher {

	public function match(ReflectionMethod $reflectionMethod);

}
