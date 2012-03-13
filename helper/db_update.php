<?php
class DbUpdateHelper extends Helper {
	public static function update() {
		TableVersionsDao::get()->initSchema();
		if ($handle = opendir(BASE_PATH . '/model/')) {
			/* ディレクトリをループする際の正しい方法です */
			while (false !== ($file = readdir($handle))) {
				echo "$file\n";
			}
			
			closedir($handle);
		}
	}
	
	public static function _update($path = '') {
		if ($handle = opendir(BASE_PATH . '/model/' . $path)) {
			/* ディレクトリをループする際の正しい方法です */
			while (false !== ($file = readdir($handle))) {
				if (is_dir($path . $file)) {
					self::_update($path . $file . '/');
				}
				
				if (pathinfo($path . $file,PATHINFO_EXTENSION) == 'php') {
					$pathArr = explode('/',$path);
					$tempArr = array();
					foreach ($pathArr as $val) {
						$tempArr = array_merge($tempArr,array_reverse(explode('_',$val)));
					}
					$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr)));
					
					$obj = call_user_func(array($clName . '::get'));
					call_user_func(array($obj,'initSchema'));
				}
			}
			closedir($handle);
		}
	}
}