<?php
class CacheVoid {
	public function isExists($key) {
		return false;
	}

	public function get($key) {
		return null;
	}

	public function set($key ,$value) {

	}

	public function clear() {

	}
}

Cache::setInstance(new CacheVoid());