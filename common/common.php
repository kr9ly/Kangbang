<?php

define('BASE_PATH',realpath('../'));

require 'helper.php';
require 'loader.php';

class ErrorException extends Exception
{
	public function __construct($errno, $errstr, $errfile, $errline) {
		$errlev = array(
				E_USER_ERROR   => 'FATAL',
				E_ERROR        => 'FATAL',
				E_USER_WARNING => 'WARNING',
				E_WARNING      => 'WARNING',
				E_USER_NOTICE  => 'NOTICE',
				E_NOTICE       => 'NOTICE',
				E_STRICT       => 'E_STRICT'
		);

		$add_msg= (string)$errno;
		if (isset($errlev[$errno])) {
			$add_msg = $errlev[$errno] . ' : ';
		}
		parent::__construct($add_msg . $errstr, $errno);
		$this->file = $errfile;
		$this->line = $errline;
	}

	public static function handler($errno, $errstr, $errfile, $errline) {
		throw new ErrorException($errno, $errstr, $errfile, $errline);
	}
}

set_error_handler('ErrorException::handler');

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