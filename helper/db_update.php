<?php
class DbUpdateHelper extends Helper {
	public static function update() {
		TableVersionsDao::get()->initSchema();
		self::_update();
	}

	private static function _update($path = '') {
		if ($handle = opendir(BASE_PATH . '/model/' . $path)) {
			while (false !== ($file = readdir($handle))) {
				if (strpos($file,'.') === 0) {
					continue;
				}
				if (is_dir(BASE_PATH . '/model/' . $path . $file)) {
					self::_update($path . $file . '/');
				}
				if (pathinfo($path . $file,PATHINFO_EXTENSION) == 'php') {
					$pathArr = explode('/',$path . pathinfo($path . $file,PATHINFO_FILENAME));
					if (count($pathArr) > 1 && $pathArr[count($pathArr)-1] == $pathArr[count($pathArr)-2]) {
						array_pop($pathArr);
					}
					$tempArr = array();
					foreach ($pathArr as $val) {
						$tempArr = array_merge($tempArr,array_reverse(explode('_',$val)));
					}
					$tempArr = array_filter($tempArr,'strlen');
					$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'Dao';
					$obj = call_user_func($clName . '::get');
					call_user_func(array($obj,'initSchema'));
				}
			}
			closedir($handle);
		}
	}
}