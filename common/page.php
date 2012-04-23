<?php
class Page extends Base {
	/* static */
	public static function getInstance() {
		return self::initInstance(new static(),'',array());
	}

	public static function getByPath($path) {
		if (!$path) {
			$path = 'index';
		}
		$dirArray = explode('/',$path);
		$pathArray = array();
		foreach ($dirArray as $dir) {
			$pathArray = array_merge($pathArray,array_reverse(explode('_',$dir)));
		}
		$params = array();
		while (count($pathArray) > 0) {
			$name = TextHelper::toCamelCase(implode('_', array_reverse($pathArray))) . 'Page';
			if (Loader::classExists($name)) {
				$page = new $name;
				$action = '';
				if ($params > 0 && method_exists($page, $params[0])) {
					$action = array_shift($params);
				}
				return self::initInstance(new $name, $action, $params);
			}
			array_unshift($params,array_pop($pathArray));
		}
		return null;
	}

	public static function execByPath($path) {
		$page = self::getByPath($path);
		if ($page) {
			return call_user_func_array(array($page,$page->getAction()),$page->getParams());
		}
	}

	private static function initInstance($page,$action,$params) {
		$page->action = $action ? $action : 'exec';
		$page->params = $params;
		return $page;
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

	protected function isGet() {
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}

	protected function isPost() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
}