<?php

namespace Example\Model\Facade;

use Example\Model\Entity\Article;

class ArticleModelFacade {
	public function fetchArticleById($articleId) {
		$article = new Article();
		// fetch ...
		$article->setName('name');
		$article->setText('text');

		return $article;
	}
}
