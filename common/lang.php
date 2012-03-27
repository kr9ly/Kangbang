<?php
class Lang {
	public static function loadLang($path, $file) {
		$conf = Config::get('locale_list');
		$langList = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$lang = DEFAULT_LANG;
		foreach ($langList as $key) {
			if ($conf->key_exists($key)) {
				$lang = $conf->_get($key);
				break;
			}
		}
		if (Cache::isExists('lang/' . $path . $file)) {
			return Cache::get('lang/' . $path . $file);
		} else {
			if (is_file(BASE_PATH . $path . $file . '.' . $lang . '.ini')) {
				$res = parse_ini_file(BASE_PATH . $path . $file . '.' . $lang . '.ini');
			} else if (is_file(BASE_PATH . $path . 'lang/' . $file . '.' . $lang . '.ini')) {
				$res = parse_ini_file(BASE_PATH . $path . 'lang/' . $file . '.' . $lang . '.ini');
			} else {
				return;
			}
			Cache::set('lang/' . $path . $file, $res);
			return $res;
		}
	}
}