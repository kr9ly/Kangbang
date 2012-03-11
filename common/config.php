<?php
class Config implements Iterator {
	static $confs = array();

	public static function get($path) {
		if (self::$confs[$path]) {
			return self::$confs[$path];
		}
		if (is_file(BASE_PATH . '/config/' . $path . '.json')) {
			self::$confs[$path] = new Config();
			self::$confs[$path]->conf = json_decode(file_get_contents(BASE_PATH . '/config/' . $path . '.json'),true);
		} else {
			self::$confs[$path] = new Config();
			self::$confs[$path]->conf = array();
		}
		self::$confs[$path]->path = $path;
		return self::$confs[$path];
	}

	private $path;
	private $conf;
	private $lang = array();

	final private function __construct() {

	}

	function rewind() {
		reset($this->conf);
	}

	function current() {
		if (is_array(current($this->conf))) {
			$conf = new Config();
			$conf->path = $this->path;
			$conf->conf = current($this->conf);
			return $conf;
		}
		return current($this->conf);
	}

	function key() {
		return $this->_(key($this->conf));
	}

	function next() {
		next($this->conf);
	}

	function valid() {
		return current($this->conf) !== false;
	}

	function __get($key) {
		$escape = true;
		if (strpos($key, '_') === 0) {
			$escape = false;
			$key = substr($key, 1);
		}
		if (array_key_exists($key, $this->conf)) {
			if (is_array($this->conf[$key])) {
				$conf = new Config();
				$conf->path = $this->path;
				$conf->conf = $this->conf[$key];
				return $conf;
			}
			return $escape ? htmlspecialchars($this->_($this->conf[$key])) : $this->_($this->conf[$key]);
		}
		return '';
	}

	function _($key) {
		if (!$this->lang) {
			$this->lang = Lang::loadLang(pathinfo('/config/' . $this->path,PATHINFO_DIRNAME) . '/', pathinfo('/config/' . $this->path,PATHINFO_FILENAME));
		}
		if (array_key_exists($key, $this->lang)) {
			return $this->lang[$key];
		}
		return $key;
	}
}