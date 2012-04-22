<?php
class Base {
	static $langs = array();
	static $global;
	private $lang;

	public static function getInstance() {
		return new static();
	}

	public function getClassPath() {
		return Loader::getClassPath(get_class($this));
	}

	public function getClassFile() {
		return Loader::getClassFile(get_class($this));
	}

	public function loadLang() {
		if (!$this->lang) {
			$this->lang = Lang::loadLang($this->getClassPath(), $this->getClassFile());
		}
	}

	public function _() {
		$args = func_get_args();
		$key = array_shift($args);
		$this->loadLang();
		$arr = array();
		foreach ($args as $val) {
			$arr[] = $this->_($val);
		}
		$args = $arr;
		if ($this->lang[$key]) {
			array_unshift($args, $this->lang[$key]);
			return call_user_func_array('sprintf', $args);
		}
		if (get_parent_class($this) && method_exists(get_parent_class($this),'get')) {
			$parent = call_user_func(get_parent_class($this) . '::get');
			array_unshift($args, $key);
			return call_user_func_array(array($parent, '_'), $args);
		}
		if (!is_array(self::$global)) {
			if (is_file(BASE_PATH . '/lang/lang.' . DEFAULT_LANG . '.ini')) {
				self::$global = parse_ini_file(BASE_PATH . '/lang/lang.' . DEFAULT_LANG . '.ini');
			} else {
				self::$global = array();
			}
		}
		if (array_key_exists($key, self::$global)) {
			return sprintf(self::$global[$key], $args);
		}
		return $key;
	}
}