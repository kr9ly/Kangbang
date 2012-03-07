<?php

define('BASE_PATH',realpath('../'));

date_default_timezone_set('Asia/Tokyo');

require 'helper.php';
require 'loader.php';

require '../config/site.php';

Library::load('session_' . SESSION_TYPE);