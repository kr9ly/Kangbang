<?php

spl_autoload_register('Loader::loadClass');

class Loader {
	private static $classDirs = array();

	public static function getClassPath($name) {
		return self::$classDirs[$name];
	}

	public static function loadClass($name) {
		$basePath = self::searchClass($name);
		self::$classDirs[$name] = preg_replace("/^" . str_replace('/', '\/', BASE_PATH) . "/u","",pathinfo($basePath . '.php',PATHINFO_DIRNAME)) . '/';

		require $basePath . '.php';
	}

	public static function classExists($name) {
		$basepath = self::searchClass($name);

		return is_file($basepath . '.php');
	}

	private static function searchClass($name) {
		$array = Helper::_toCamelArray($name);
		$basePath = BASE_PATH;
		$prefix = '';
		if (count($array) > 1) {
			switch (end($array)) {
				case 'Controller':
					$basePath .= '/controller';
					array_pop($array);
					break;
				case 'Dao':
					$basePath .= '/model';
					array_pop($array);
					break;
				case 'Parts':
					$basePath .= '/parts';
					array_pop($array);
					break;
				case 'Helper':
					$basePath .= '/helper';
					array_pop($array);
					break;
				case 'View':
					$basePath .= '/view';
					array_pop($array);
					break;
				default:
					$basePath .= '/common';
					break;
			}
		} else {
			$basePath .= '/common';
		}
		foreach ($array as $val) {
			if (is_dir($basePath)) {
				$basePath .= '/';
			} else {
				$basePath .= '_';
			}
			$basePath .= strtolower($val);
		}

		if (is_dir($basePath)) {
			$basePath .= '/' . strtolower(basename($basePath));
		}

		return $basePath;
	}
}