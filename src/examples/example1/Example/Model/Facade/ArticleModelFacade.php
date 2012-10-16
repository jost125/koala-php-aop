<?php

namespace Example\Model\Facade;

class ArticleModelFacade {
	public function fetchArticleById($articleId) {
		$article = new \Example\Model\Entity\Article();
		// fetch ...
		$article->setName('name');
		$article->setText('text');

		return $article;
	}
}
