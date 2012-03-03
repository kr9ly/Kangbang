<?php
class View {
	private static $instances = array();
	private static $activeView;

	/* static start */
	public static function __callstatic($method, $argument) {
		if (!array_key_exists(get_called_class(), self::$instances)) {
			self::$instances[get_called_class()] = new static();
		}
		self::$activeView = self::$instances[get_called_class()];
		return call_user_func_array(array(self::$activeView,$method), $argument);
	}

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
		}
	}
	/* static end */

	public function display() {

	}
}