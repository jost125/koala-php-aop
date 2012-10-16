<?php

namespace Example\Controller;

class ArticleController {
	private $articleModelFacade;

	public function __construct(\Example\Model\Facade\ArticleModelFacade $articleModelFacade) {
		$this->articleModelFacade = $articleModelFacade;
	}

	public function actionArticle() {
		$article = $this->articleModelFacade->fetchArticleById(5);
		echo $article->getName() . "\n";
		echo $article->getText() . "\n";
	}
}
