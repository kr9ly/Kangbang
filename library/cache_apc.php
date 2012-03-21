<?php
class CacheApc {
	public function isExists($key) {
		return apc_fetch($key) !== false;
	}

	public function get($key) {
		return apc_fetch($key);
	}

	public function set($key ,$value) {
		apc_store($key, $value);
	}

	public function clear() {
		apc_clear_cache('user');
	}
}

Cache::setInstance(new CacheApc());