<?php

namespace Example\DAO;

use Exception;
use Example\Transactional;

class UserDAO {

	private $users = [
		'John' => [
			'firstname' => 'John',
			'lastname' => 'Doe',
			'age' => 25,
		]
	];

	/**
	 * @Transactional
	 */
	public function findByFirstName($name) {
		$name = $this->removeWS($name);
		if (!isset($this->users[$name])) {
			throw new Exception('No user with name ' . $name);
		}
		return $this->users[$name];
	}

	protected function removeWS($string) {
		return trim($string);
	}

}
