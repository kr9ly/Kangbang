<?php
class Parts {
	/* static */
	public static function display($path) {
		if (is_file(BASE_PATH . '/parts/' . $path . '.php')) {
			$clName = TextHelper::toCamelCase(str_replace('/','_',$path)) . 'Parts';
			call_user_func_array(array(new $clName, 'exec'), func_get_args());
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