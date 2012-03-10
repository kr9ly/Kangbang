<?php
class Parts extends Base {
	/* static */
	public static function display($path) {
		if (is_file(BASE_PATH . '/parts/' . $path . '.php') || is_file(BASE_PATH . '/parts/' . $path . '/' . pathinfo($path,PATHINFO_FILENAME) . '.php')) {
			$args = func_get_args();
			array_shift($args);
			$clName = TextHelper::toCamelCase(implode('_',array_reverse(explode('_',str_replace('/','_',$path))))) . 'Parts';
			call_user_func_array(array(new $clName, 'exec'), $args);
		}
		TemplateView::get()->setPath('/parts/' . $path);
		View::displayView();
	}
	/* static end */

	public function exec() {

	}

	protected function setParam($name, $value) {
		TemplateView::get()->setParam($name, $value);
	}
}