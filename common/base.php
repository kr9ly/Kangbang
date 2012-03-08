<?php
class Base {
	static $langs = array();
	private $lang;

	public function getClassPath() {
		return Loader::getClassPath(get_class($this));
	}

	public function getClassFile() {
		return Loader::getClassFile(get_class($this));
	}

	public function loadLang() {
		if (!$this->lang) {
			if (self::$langs[get_class($this)]) {
				$this->lang = self::$langs[get_class($this)];
			} else {
				if (is_file(BASE_PATH . $this->getClassPath() . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini')) {
					$this->lang = parse_ini_file(BASE_PATH . $this->getClassPath() . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini');
				} else if (is_file(BASE_PATH . $this->getClassPath() . 'lang/' . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini')) {
					$this->lang = parse_ini_file(BASE_PATH . $this->getClassPath() . 'lang/' . $this->getClassFile() . '.' . DEFAULT_LANG . '.ini');
				} else {
					return;
				}
				self::$langs[get_class($this)] = $this->lang;
			}
		}
	}

	public function _() {
		$args = func_get_args();
		$key = array_shift($args);
		$this->loadLang();
		if ($this->lang[$key]) {
			array_unshift($args, $this->lang[$key]);
			return call_user_func_array('sprintf', $args);
		}
		if (get_parent_class($this) && method_exists(get_parent_class($this),'get')) {
			$parent = call_user_func(get_parent_class($this) . '::get');
			array_unshift($args, $key);
			return call_user_func_array(array($parent, '_'), $args);
		}
		return $key;
	}
}