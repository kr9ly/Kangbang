<?php
class Cache {
	private static $instance;

	public static function setInstance($instance) {
		self::$instance = $instance;
	}

	public static function isExists($key) {
		return self::$instance->isExists($key);
	}

	public static function get($key) {
		return self::$instance->get($key);
	}

	public static function set($key ,$value) {
		self::$instance->set($key, $value);
	}

	public static function clear() {
		self::$instance->clear();
	}
}