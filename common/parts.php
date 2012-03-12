<?php
class Parts extends Base {
	/* static */
	public static function display($path) {
		$args = func_get_args();
		array_shift($args);
		if (Cache::isExists('parts/' . $path)) {
			$clName = Cache::get('parts/' . $path);
			call_user_func_array(array(new $clName, 'exec'), $args);
		} else {
			$pathArray = explode('/', $path);
			while (count($pathArray) > 0) {
				$temp = implode('/',$pathArray);
				if (is_file(BASE_PATH . '/parts/' . $temp . '.php') || is_file(BASE_PATH . '/parts/' . $temp . '/' . pathinfo($temp,PATHINFO_FILENAME) . '.php')) {
					$tempArray = array();
					foreach ($pathArray as $dir) {
						$tempArray = array_merge($tempArray,array_reverse(explode('_',$dir)));
					}
					$clName = TextHelper::toCamelCase(implode('_', array_reverse($tempArray))) . 'Parts';
					call_user_func_array(array(new $clName, 'exec'), $args);
					Cache::set('parts/' . $path,$clName);
					break;
				}
				array_pop($pathArray);
			}
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