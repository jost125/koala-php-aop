<?php

namespace Example\Controller;

use Example\Model\Facade\ArticleModelFacade;

class ArticleController {
	private $articleModelFacade;
	private $welcomeMessage;

	public function __construct($welcomeMessage, ArticleModelFacade $articleModelFacade) {
		$this->articleModelFacade = $articleModelFacade;
		$this->welcomeMessage = $welcomeMessage;
	}

	public function actionArticle() {
		echo $this->welcomeMessage . "\n";
		$article = $this->articleModelFacade->fetchArticleById(5);
		echo $article->getName() . "\n";
		echo $article->getText() . "\n";
	}
}
