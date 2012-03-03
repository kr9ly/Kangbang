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
		extract($this->params);
		eval('?>' . $template);
		echo ob_get_clean();
	}

}