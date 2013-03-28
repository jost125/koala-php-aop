<?php

namespace Example\Controller;

use Example\Model\Facade\ArticleModelFacade;

class ArticleController {
	private $articleModelFacade;

	public function __construct(ArticleModelFacade $articleModelFacade) {
		$this->articleModelFacade = $articleModelFacade;
	}

	public function actionArticle() {
		$article = $this->articleModelFacade->fetchArticleById(5);
		echo $article->getName() . "\n";
		echo $article->getText() . "\n";
	}
}
