<?php
class View extends Base {
	/* static start */
	private static $instances = array();
	private static $activeView;

	public static function get() {
		if (self::$activeView) {
			return self::$activeView;
		}
		self::$activeView = new static();
		return self::$activeView;
	}

	public static function getLocal() {
		return new static();
	}


	public static function displayView() {
		if (self::$activeView) {
			$view = self::$activeView;
			self::$activeView = null;
			return $view->display();
		}
		return false;
	}

	public static function getByPath($path) {
		if (!$path) $path = 'index';
		$pathArray = explode('/',$path);
		$pathArray = array_filter($pathArray,'strlen');
		while (count($pathArray) > 0) {
			$search = implode('/',$pathArray);
			if (is_file(BASE_PATH . '/controller/' . $search . '.tpl.php')) {
				$view = TemplateView::get();
				$view->setPath('/controller/' . $search);
				return $view;
			} else if (is_file(BASE_PATH . '/controller/' . $search . '/' . pathinfo(BASE_PATH . '/controller/' . $search . '.tpl.php',PATHINFO_FILENAME) . '.php')) {
				$view = TemplateView::get();
				$view->setPath('/controller/' . $search . '/' . pathinfo(BASE_PATH . '/controller/' . $search . '.php',PATHINFO_FILENAME));
				return $view;
			}
			array_pop($pathArray);
		}
		return null;
	}
	/* static end */

	public function display() {

	}
}