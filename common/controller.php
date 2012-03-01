<?php
class Controller {
	final private function __construct() {

	}

	public static function getInstance() {
		$controller = new static();
		return $controller;
	}
}