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
			$view->display();
			return true;
		}
		return false;
	}

	public static function getByPath($path) {
		if (!$path) $path = 'index';
		$path = preg_replace('/\/$/u','',$path);
		if (is_file(BASE_PATH . '/controller/' . $path . '.tpl.php')) {
			$view = TemplateView::get();
			$view->setPath('/controller/' . $path);
			return $view;
		} else if (is_file(BASE_PATH . '/controller/' . $path . '/' . pathinfo(BASE_PATH . '/controller/' . $path . '.tpl.php',PATHINFO_FILENAME) . '.php')) {
			$view = TemplateView::get();
			$view->setPath('/controller/' . $path . '/' . pathinfo(BASE_PATH . '/controller/' . $path . '.php',PATHINFO_FILENAME));
			return $view;
		}
		return null;
	}
	/* static end */

	public function display() {

	}
}