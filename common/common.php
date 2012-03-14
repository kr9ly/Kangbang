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
	
	Controller::execByPath('admin/install');
	View::displayView();
}