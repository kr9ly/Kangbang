<?php

class Test {
	private static $results = array();

	public static function assert($function, $assertion, $message, $args = array()) {
		$trace = debug_backtrace();

		if (!self::$results[$trace[1]['class']]) {
			self::$results[$trace[1]['class']] = array();
		}

		try {
			$val = call_user_func_array($function, $args);
			$res = eval('return $val ' . $assertion);
			if (!$res) {
				throw new Exception();
			}
			self::$results[$trace[1]['class']][$message] = true;
		} catch (Exception $e) {
			self::$results[$trace[1]['class']][$message] = false;
		}
	}

	public static function doAllTests($path = '') {
		define('UNITTEST',true);
		define('DB_DATABASE_TEST',DB_DATABASE . '_test');

		DbUpdateHelper::update(true);

		if ($handle = opendir(BASE_PATH . '/' . $path)) {
			while (false !== ($file = readdir($handle))) {
				if (strpos($file,'.') === 0) {
					continue;
				}
				if (is_dir(BASE_PATH . '/' . $path . $file) && $file != 'library') {
					self::doAllTests($path . $file . '/',$init);
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

					switch ($pathArr[0]) {
						case 'page':
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'Page';
							break;
						case 'model':
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'Dao';
							break;
						case 'parts':
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'Parts';
							break;
						case 'helper':
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'Helper';
							break;
						case 'view':
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'View';
							break;
						case 'filter':
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr))) . 'Filter';
							break;
						default:
							$clName = TextHelper::toCamelCase(implode('_',array_reverse($tempArr)));
							break;
					}

					if (Loader::classExists($clName) && is_callable($clName . '::unittest')) {
						call_user_func($clName . '::unittest');
					}
				}
			}
			closedir($handle);
		}
	}
}
