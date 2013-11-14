<?php

namespace Example\Controller;

use Example\DAO\UserDAO;
use Example\JsonResponse;
use Example\FileTemplateResponse;

/**
 * @FileTemplateResponse
 */
class HiController {

	private $hiMessage;
	private $userDAO;

	public function __construct(UserDAO $userDAO) {
		$this->userDAO = $userDAO;
	}

	public function setHiMessage($hiMessage) {
		$this->hiMessage = $hiMessage;
	}

	public function sayHiAction($firstName) {
		return [
			'message' => "$this->hiMessage $firstName",
			'user' => $this->userDAO->findByFirstName($firstName),
		];
	}

}
