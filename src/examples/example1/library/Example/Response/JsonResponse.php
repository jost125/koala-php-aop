<?php

namespace Example\Response;

class JsonResponse {

	private $variables;

	public function __construct(array $variables) {
		$this->variables = $variables;
	}

	public function render() {
		echo json_encode($this->variables);
	}

}
