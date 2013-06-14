<?php

namespace AOP\Pointcut\Parser;

interface Stream {

	const EOF = -1;

	public function peek();
	public function read();
	public function pointer();
	public function readSection($start, $end);

}
