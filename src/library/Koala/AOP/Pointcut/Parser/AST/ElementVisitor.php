<?php

namespace Koala\AOP\Pointcut\Parser\AST;

use Koala\AOP\Pointcut\Parser\AST\Element\AnnotationClassExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\AnyArguments;
use Koala\AOP\Pointcut\Parser\AST\Element\Argument;
use Koala\AOP\Pointcut\Parser\AST\Element\ArgumentsExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\ClassExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\ExecutionPointcut;
use Koala\AOP\Pointcut\Parser\AST\Element\MethodAnnotatedPointcut;
use Koala\AOP\Pointcut\Parser\AST\Element\MethodExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\Modifier;
use Koala\AOP\Pointcut\Parser\AST\Element\NoArguments;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpression;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupEnd;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutExpressionGroupStart;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutOperator;
use Koala\AOP\Pointcut\Parser\AST\Element\PointcutType;

interface ElementVisitor {

	public function acceptAnyArguments(AnyArguments $anyArguments);
	public function acceptArgument(Argument $argument);
	public function acceptArgumentsExpression(ArgumentsExpression $argumentsExpression);
	public function acceptAnnotationClassExpression(AnnotationClassExpression $annotationClassExpression);
	public function acceptClassExpression(ClassExpression $classExpression);
	public function acceptMethodExpression(MethodExpression $methodExpression);
	public function acceptModifier(Modifier $modifier);
	public function acceptNoArguments(NoArguments $noArguments);
	public function acceptExecutionPointcut(ExecutionPointcut $pointcut);
	public function acceptMethodAnnotatedPointcut(MethodAnnotatedPointcut $pointcut);
	public function acceptPointcutExpression(PointcutExpression $pointcutExpression);
	public function acceptPointcutExpressionGroupStart(PointcutExpressionGroupStart $pointcutExpressionGroupStart);
	public function acceptPointcutExpressionGroupEnd(PointcutExpressionGroupEnd $pointcutExpressionGroupEnd);
	public function acceptPointcutOperator(PointcutOperator $pointcutOperator);
	public function acceptPointcutType(PointcutType $pointcutType);

}
