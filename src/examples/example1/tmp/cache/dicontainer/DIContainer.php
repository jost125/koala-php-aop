<?php

class DIContainer {
	private $services = array();

	public function getService($serviceId) {
		if (!$this->isServiceCreated($serviceId)) {
			$this->createService($serviceId);
		}
		return $this->getServiceInstance($serviceId);
	}

	private function isServiceCreated($serviceId) {
		return array_key_exists($serviceId, $this->services);
	}

	private function createService($serviceId) {
		$methodName = 'createService' . ucfirst($serviceId);
		$this->services[$serviceId] = $this->$methodName();
	}

	private function getServiceInstance($serviceId) {
		return $this->services[$serviceId];
	}

	private function createServiceArticleController() {
		return new \Example\Controller\ArticleController($this->getService('articleModelFacade'));
	}

	private function createServiceArticleModelFacade() {
//		return new \Example\Model\Facade\ArticleModelFacade();
		return new GeneratedAOPProxy\Example\Model\Facade\ArticleModelFacade($this->getService('logger'), $this->getService('stopwatchFactory'));
	}

	private function createServiceLogger() {
		return new \Example\Logger\StdLogger();
	}

	private function createServiceStopwatchFactory() {
		return new \Example\Stopwatch\StopwatchFactory();
	}
}
