<?php

namespace Example\Response;

class FileTemplateResponse {

	private $file;
	private $variables;

	public function __construct($file, array $variables) {
		$this->file = $file;
		$this->variables = $variables;
	}

	public function render() {
		var_dump($this);
	}

}
