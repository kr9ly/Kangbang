<?php
class Controller {
	final private function __construct() {

	}

	public static function getInstance() {
		return self::initInstance(new static(),array());
	}

	public static function getByPath($path) {
		if (!$path) {
			$path = 'index';
		}
		$pathArray = explode('/',$path);
		$params = array();
		while (count($pathArray) > 0) {
			$name = TextHelper::toCamelCase(implode('', $pathArray)) . 'Controller';
			if (Loader::classExists($name)) {
				return self::initInstance(new $name, $params);
			}
			array_unshift($params,array_pop($pathArray));
		}
		return null;
	}

	private static function initInstance($controller,$params) {
		$controller->params = $params;
		return $controller;
	}

	private $params;
}