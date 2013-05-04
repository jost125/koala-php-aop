<?php

namespace Reflection\Annotation;

interface Annotation {
	public function getName();
	public function hasParameters();
	public function getParameters();
	public function getParameter($name);
	public function toExpression();
}
