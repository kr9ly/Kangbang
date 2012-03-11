<?php
class Lang {
	public static function loadLang($path, $file) {
		if (Cache::isExists('lang/' . $path . $file)) {
			return Cache::get('lang/' . $path . $file);
		} else {
			if (is_file(BASE_PATH . $path . $file . '.' . DEFAULT_LANG . '.ini')) {
				$lang = parse_ini_file(BASE_PATH . $path . $file . '.' . DEFAULT_LANG . '.ini');
			} else if (is_file(BASE_PATH . $path . 'lang/' . $file . '.' . DEFAULT_LANG . '.ini')) {
				$lang = parse_ini_file(BASE_PATH . $path . 'lang/' . $file . '.' . DEFAULT_LANG . '.ini');
			} else {
				return;
			}
			Cache::set('lang/' . $path . $file, $lang);
			return $lang;
		}
	}
}