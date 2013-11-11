<?php

namespace AOP\Pointcut\Parser\AST;

use AOP\Pointcut\Parser\AST\Element\AnyArguments;
use AOP\Pointcut\Parser\AST\Element\Argument;
use AOP\Pointcut\Parser\AST\Element\ArgumentsExpression;
use AOP\Pointcut\Parser\AST\Element\ClassExpression;
use AOP\Pointcut\Parser\AST\Element\MethodExpression;
use AOP\Pointcut\Parser\AST\Element\Modifier;
use AOP\Pointcut\Parser\AST\Element\NoArguments;
use AOP\Pointcut\Parser\AST\Element\Pointcut;
use AOP\Pointcut\Parser\AST\Element\PointcutExpression;
use AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupEnd;
use AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupStart;
use AOP\Pointcut\Parser\AST\Element\PointcutOperator;
use AOP\Pointcut\Parser\AST\Element\PointcutType;

interface ElementVisitor {

	public function acceptAnyArguments(AnyArguments $anyArguments);
	public function acceptArgument(Argument $argument);
	public function acceptArgumentsExpression(ArgumentsExpression $argumentsExpression);
	public function acceptClassExpression(ClassExpression $classExpression);
	public function acceptMethodExpression(MethodExpression $methodExpression);
	public function acceptModifier(Modifier $modifier);
	public function acceptNoArguments(NoArguments $noArguments);
	public function acceptPointcut(Pointcut $pointcut);
	public function acceptPointcutExpression(PointcutExpression $pointcutExpression);
	public function acceptPointcutExpressionGroupStart(PointcutExpressionGroupStart $pointcutExpressionGroupStart);
	public function acceptPointcutExpressionGroupEnd(PointcutExpressionGroupEnd $pointcutExpressionGroupEnd);
	public function acceptPointcutOperator(PointcutOperator $pointcutOperator);
	public function acceptPointcutType(PointcutType $pointcutType);

}
