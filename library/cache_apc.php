<?php
class Cache {
	public static function isExists($key) {
		return apc_fetch($key) !== false;
	}

	public static function get($key) {
		return apc_fetch($key);
	}

	public static function set($key ,$value) {
		apc_store($key, $value);
	}

	public static function clear() {
		apc_clear_cache('user');
	}
}