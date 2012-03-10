<?php
class TemplateView extends View {
	private $path;
	private $params = array();

	public function setParam($name, $value) {
		$this->params[$name] = $value;
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function display() {
		$template = file_get_contents(BASE_PATH . $this->path . '.tpl.php');
		ob_start();
		eval('?>' . $template);
		echo ob_get_clean();
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
}