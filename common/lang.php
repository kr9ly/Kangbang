<?php
class Lang {
	static $langs = array();

	public static function loadLang($path, $file) {
		if (self::$langs[$path . $file]) {
			return self::$langs[$path . $file];
		} else {
			if (is_file(BASE_PATH . $path . $file . '.' . DEFAULT_LANG . '.ini')) {
				self::$langs[$path . $file] = parse_ini_file(BASE_PATH . $path . $file . '.' . DEFAULT_LANG . '.ini');
			} else if (is_file(BASE_PATH . $path . 'lang/' . $file . '.' . DEFAULT_LANG . '.ini')) {
				self::$langs[$path . $file] = parse_ini_file(BASE_PATH . $path . 'lang/' . $file . '.' . DEFAULT_LANG . '.ini');
			} else {
				return;
			}
			return self::$langs[$path . $file];
		}
	}
}