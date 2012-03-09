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
		if (strpos($key,'_')) {
			return $this->params[$key];
		}
		return htmlspecialchars($this->params[$key]);
	}
	
	private function get($key) {
		return htmlspecialchars($_GET[$key]);
	}
	
	private function post($key) {
		return htmlspecialchars($_POST[$key]);
	}
	
	private function request($key) {
		return htmlspecialchars($_REQUEST[$key]);
	}
	
	private function _get($key) {
		return $_GET[$key];
	}
	
	private function _post($key) {
		return $_POST[$key];
	}
	
	private function request($key) {
		return $_REQUEST[$key];
	}

}