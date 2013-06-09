<?php

interface BarInterface {
	public function bar(FooService $foo, $bar);
	public function foo(FooService $foo, $bar, $baz);
}
