<?php

namespace AOP\Interceptor;

use ReflectionMethod;

interface Loader {

	public function loadInterceptors(ReflectionMethod $reflectionMethod);

}
