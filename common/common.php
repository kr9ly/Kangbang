<?php

define('BASE_PATH',realpath('../'));

require 'helper.php';
require 'loader.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

function error_exception_handler($errno, $errstr, $errfile, $errline) {
	if (!(error_reporting() & $errno)) {
		return;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('error_exception_handler');

if (is_file('../config/site.php')) {
	require '../config/site.php';

	define('BASE_URL', 'http://' . $_SERVER["SERVER_NAME"] . pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));
	if (ENABLE_HTTPS) {
		define('BASE_SSL_URL', 'https://' . $_SERVER["SERVER_NAME"] . pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));
	} else {
		define('BASE_SSL_URL', 'http://' . $_SERVER["SERVER_NAME"] . pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));
	}

	date_default_timezone_set(DEFAULT_TIMEZONE);
	Library::load('cache_' . CACHE_TYPE);
	Library::load('session_' . SESSION_TYPE);
} else {
	define('INSTALL_MODE', true);
	define('BASE_URL', 'http://' . $_SERVER["SERVER_NAME"] . pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME));
	define('DEFAULT_LANG', 'ja_JP');
	define('DEFAULT_TIMEZONE', 'Asia/Tokyo');
	date_default_timezone_set(DEFAULT_TIMEZONE);

	Library::load('cache_void');

	View::getByPath('admin/install');
	Page::execByPath('admin/install');

	View::displayView();
	exit;
}