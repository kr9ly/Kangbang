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
		if (Cache::isExists('template/' . $this->path)) {
			$template = Cache::get('template/' . $this->path);
		} else if (is_file(BASE_PATH . $this->path . '.tpl.php')) {
			$template = str_replace("\t","",file_get_contents(BASE_PATH . $this->path . '.tpl.php'));
			Cache::set('template/' . $this->path, $template);
		} else if (is_file(BASE_PATH . $this->path . '/' . pathinfo($this->path,PATHINFO_FILENAME) . '.tpl.php')) {
			$template = str_replace("\t","",file_get_contents(BASE_PATH . $this->path . '/' . pathinfo($this->path,PATHINFO_FILENAME) . '.tpl.php'));
			Cache::set('template/' . $this->path, $template);
		} else {
			return false;
		}
		ob_start();
		eval('?>' . $template);
		ob_end_flush();
		return true;
	}

	public function __get($key) {
		$escape = true;
		if (strpos($key,'_') === 0) {
			$escape = false;
			$key = substr($key,1);
		}
		if (array_key_exists($key,$this->params)) {
			if (is_array($this->params[$key]) || is_object($this->params[$key])) {
				return $this->params[$key];
			}
			return $escape ? htmlspecialchars($this->params[$key]) : $this->params[$key];
		}
		return '';
	}

	private function http($url = false) {
		return UrlHelper::http($url);
	}

	private function https($url = false) {
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
			$this->lang = Lang::loadLang(pathinfo($this->path,PATHINFO_DIRNAME) . '/', pathinfo($this->path,PATHINFO_FILENAME));
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