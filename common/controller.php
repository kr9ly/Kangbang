<?php
class Controller extends Base {
	/* static */
	public static function getInstance() {
		return self::initInstance(new static(),'',array());
	}

	public static function getByPath($path) {
		if (!$path) {
			$path = 'index';
		}
		$pathArray = explode('/',str_replace('_','/',$path));
		$params = array();
		while (count($pathArray) > 0) {
			$name = TextHelper::toCamelCase(implode('_', array_reverse($pathArray))) . 'Controller';
			if (Loader::classExists($name)) {
				$controller = new $name;
				$action = '';
				if ($params > 0 && method_exists($controller, $params[0])) {
					$action = array_shift($params);
				}
				return self::initInstance(new $name, $action, $params);
			}
			array_unshift($params,array_pop($pathArray));
		}
		return null;
	}

	public static function execByPath($path) {
		$controller = self::getByPath($path);
		if ($controller) {
			return call_user_func_array(array($controller,$controller->getAction()),$controller->getParams());
		}
	}

	private static function initInstance($controller,$action,$params) {
		$controller->action = $action ? $action : 'exec';
		$controller->params = $params;
		return $controller;
	}
	/* static end */
	private $action;
	private $params;

	final private function __construct() {

	}

	public function exec() {

	}

	public function getAction() {
		return $this->action;
	}

	public function getParams() {
		return $this->params;
	}

	public function getTemplate($path) {
		if (is_file(BASE_PATH . $this->getClassPath() . $path . '.tpl.php')) {
			return $this->getClassPath() . $path;
		}
		return $this->getClassPath() . 'templates/' . $path;
	}
}