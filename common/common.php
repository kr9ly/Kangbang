<?php

define('BASE_PATH',realpath('../'));

require 'helper.php';
require 'loader.php';

require '../config/site.php';

date_default_timezone_set(DEFAULT_TIMEZONE);
Library::load('cache_' . CACHE_TYPE);
Library::load('session_' . SESSION_TYPE);