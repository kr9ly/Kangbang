<?php
class View {
	/* static start */
	private static $instances = array();
	private static $activeView;

	public static function get() {
		if (!array_key_exists(get_called_class(), self::$instances)) {
			self::$instances[get_called_class()] = new static();
		}
		self::$activeView = self::$instances[get_called_class()];
		return self::$instances[get_called_class()];
	}

	public static function getLocal() {
		return new static();
	}


	public static function displayView() {
		if (self::$activeView) {
			self::$activeView->display();
            return true;
		}
        return false;
	}
    
    public static function getByPath($path) {
        if (!$path) $path = 'index';
        if (is_file(BASE_PATH . 'controller/' . $path . '.tpl.php')) {
            $view = TemplateView::getLocal();
            $view->setPath(BASE_PATH . 'controller/' . $path . '.tpl.php');
            return $view;
        } else if (is_dir(BASE_PATH . 'controller/' . $path) && is_file(BASE_PATH . 'controller/' . $path . pathinfo(BASE_PATH . 'controller/' . $path . '.tpl.php',PATHINFO_FILENAME) . '.tpl.php')) {
            $view = TemplateView::getLocal();
            $view->setPath(BASE_PATH . 'controller/' . $path . pathinfo(BASE_PATH . 'controller/' . $path . '.tpl.php'));
            return $view;
        }
        return null;
    }
	/* static end */

	public function display() {

	}
}