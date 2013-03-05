<?php

namespace Reflection;

interface Annotation {
	public function getName();
	public function hasParameters();
	public function getParameters();
	public function toExpression();
}
