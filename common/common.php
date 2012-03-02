<?php

require 'helper.php';

define('BASE_PATH',realpath('../'));

spl_autoload_register(function($name) {
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

	require $basePath . '.php';
});