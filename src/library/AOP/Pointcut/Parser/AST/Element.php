<?php

namespace AOP\Pointcut\Parser\AST;

abstract class Element {

	abstract public function acceptVisitor(ElementVisitor $visitor);

}
