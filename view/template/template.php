<?php
class TemplateView extends View {
	static $pathLangs = array();

	private $path;
	private $params = array();

	public function setParam($name, $value) {
		$this->params[$name] = $value;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function display() {
		$template = str_replace("\t","",file_get_contents(BASE_PATH . $this->path . '.tpl.php'));
		ob_start();
		eval('?>' . $template);
		ob_end_flush();
	}

	public function __get($key) {
		$escape = true;
		if (strpos($key,'_') === 0) {
			$escape = false;
			$key = substr($key,1);
		}
		if (array_key_exists($key,$this->params)) {
			return $escape ? htmlspecialchars($this->params[$key]) : $this->params[$key];
		}
		if (array_key_exists($key,$_REQUEST)) {
			return $escape ? htmlspecialchars($_REQUEST[$key]) : $_REQUEST[$key];
		}
		return '';
	}

	private function http($url) {
		return UrlHelper::http($url);
	}

	private function https($url) {
		return UrlHelper::https($url);
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
		if (!$this->lang) {
			if (!self::$pathLangs[$this->path]) {
				if (is_file(BASE_PATH . $this->path . '.' . DEFAULT_LANG . '.ini')) {
					self::$pathLangs[$this->path] = parse_ini_file(BASE_PATH . $this->path . '.' . DEFAULT_LANG . '.ini');
				}
			}
			$this->lang = self::$pathLangs[$this->path];
		}
		if ($this->lang[$key]) {
			array_unshift($args, $this->lang[$key]);
			return call_user_func_array('sprintf', $args);
		}
		if (!is_array(self::$global)) {
			if (is_file(BASE_PATH . '/lang/lang.' . DEFAULT_LANG . '.ini')) {
				Base::$global = parse_ini_file(BASE_PATH . '/lang/lang.' . DEFAULT_LANG . '.ini');
			} else {
				Base::$global = array();
			}
		}
		if (array_key_exists($key, self::$global)) {
			return sprintf(self::$global[$key], $args);
		}
		return $key;
	}
}