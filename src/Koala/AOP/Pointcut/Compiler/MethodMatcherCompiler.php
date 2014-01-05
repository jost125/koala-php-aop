<?php

namespace Koala\AOP\Pointcut\Compiler;

use Koala\AOP\Pointcut\Parser\Lexer;
use Koala\AOP\Pointcut\PointcutExpression;
use Koala\IO\Storage\FileStorage;
use Koala\IO\Stream\StringInputStream;
use Koala\Reflection\Annotation\Parsing\AnnotationResolver;
use Koala\Reflection\MethodMatcher;

class MethodMatcherCompiler {

	private $matchersStorage;
	private $pointcutToMatcherClassTranslation;
	private $matcherNamespace;
	private $matcherDir;

	public function __construct(
		FileStorage $matchersStorage,
		PointcutToMatcherClassTranslation $pointcutToMatcherClassTranslation,
		$matcherNamespace,
		$matcherDir
	) {
		$this->matchersStorage = $matchersStorage;
		$this->pointcutToMatcherClassTranslation = $pointcutToMatcherClassTranslation;
		$this->matcherNamespace = $matcherNamespace;
		$this->matcherDir = $matcherDir;
	}

	public function compileMethodMatcher(PointcutExpression $pointcutExpression) {
		$matcherClass = $this->pointcutToMatcherClassTranslation->translate($pointcutExpression);

		if (!$this->matchersStorage->exists($matcherClass)) {
			$compiled = $this->doCompile($pointcutExpression, $matcherClass);
			$this->matchersStorage->put($matcherClass . '.php', $compiled);
		}
		$this->matchersStorage->includeOnce($matcherClass . '.php');

		return '\\' . $this->matcherNamespace . '\\' . $matcherClass;
	}

	private function doCompile(PointcutExpression $pointcutExpression, $matcherFQNClass) {
		$visitor = new CompileMethodMatchVisitor();

		$lexer = new Lexer(new StringInputStream($pointcutExpression->getExpression()));
		$tree = $lexer->buildTree();
		$tree->acceptVisitor($visitor);

		$compiledExpression = $visitor->getCompiled();

		$compiled = '<?php' . "\n";
		$compiled .= 'namespace ' . $this->matcherNamespace . ";\n";
		$compiled .= 'class ' . $matcherFQNClass . ' implements \\' . MethodMatcher::class . " {\n";
		$compiled .= ' private $annotationResolver;' . "\n";
		$compiled .= ' public function __construct(\\' . AnnotationResolver::class . ' $annotationResolver) {' . "\n";
		$compiled .= ' 	$this->annotationResolver = $annotationResolver;' . "\n";
		$compiled .= ' }' . "\n";
		$compiled .= '	public function match(\ReflectionMethod $reflectionMethod) {' . "\n";
		$compiled .= '		return ' . $compiledExpression . ";\n";
		$compiled .= '	}' . "\n";
		$compiled .= '}' . "\n";

		return $compiled;
	}

}
