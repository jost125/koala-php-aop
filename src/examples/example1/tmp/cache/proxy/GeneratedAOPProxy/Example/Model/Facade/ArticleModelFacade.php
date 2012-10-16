<?php

namespace GeneratedAOPProxy\Example\Model\Facade;

class ArticleModelFacade extends \Example\Model\Facade\ArticleModelFacade {

	private $logger;
	private $stopwatchFactory;

	public function __construct(\Example\Logger $logger, \Example\Stopwatch\StopwatchFactory $stopwatchFactory) {
		$this->logger = $logger;
		$this->stopwatchFactory = $stopwatchFactory;
	}

	/**
	 * @override
	 */
	public function fetchArticleById($articleId) {

		$stopwatch = $this->stopwatchFactory->createStopwatch();

		$stopwatch->start();
		$result = parent::fetchArticleById($articleId);

		$this->logger->log(sprintf('class: %s, method: %s, execution time: %s',
			'\Example\Model\Facade\ArticleModelFacade',
			'fetchArticleById',
			$stopwatch->stop()
		), 'info');

		return $result;
	}
}
