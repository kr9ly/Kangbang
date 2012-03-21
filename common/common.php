<?php

define('BASE_PATH',realpath('../'));

require 'helper.php';
require 'loader.php';

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
	Controller::execByPath('admin/install');

	View::displayView();
	exit;
}