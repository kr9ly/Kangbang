<?php
class Helper {
	private static $instances = array();

	final private function __construct() {

	}

	public static function _toSnakeCase($camel) {
		return strtolower(join('_', self::_toCamelArray($camel)));
	}

	public static function _toCamelCase($snake) {
		$words = self::_toSnakeArray($snake);
		$words = array_map(function ($val) {
			return ucfirst($val);
		},$words);
		return join('',$words);
	}

	public static function _toCamelArray($camel) {
		$camel = preg_replace("/([A-Z])/u","_$1",$camel);
		return array_filter(explode('_',$camel),'strlen');
	}

	public static function _toSnakeArray($snake) {
		return explode('_',$snake);
	}

	public static function __callstatic($method, $argument) {
		if (!array_key_exists(get_called_class(), self::$instances)) {
			self::$instances[get_called_class()] = new static();
		}

		return call_user_func_array(array(self::$instances[get_called_class()],$method), $argument);
	}
}