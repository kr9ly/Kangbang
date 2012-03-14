<?php

define('BASE_PATH',realpath('../'));

require 'helper.php';
require 'loader.php';

if (is_file('../config/site.php')) {
	require '../config/site.php';

	date_default_timezone_set(DEFAULT_TIMEZONE);
	Library::load('cache_' . CACHE_TYPE);
	Library::load('session_' . SESSION_TYPE);
} else {
	define('INSTALL_MODE', true);
	define('BASE_URL', 'http://' . $_SERVER["SERVER_NAME"] . '/');
	define('DEFAULT_LANG', 'ja_JP');

	Library::load('cache_void');

	View::getByPath('admin/install');
	Controller::execByPath('admin/install');

	View::displayView();
	exit;
}