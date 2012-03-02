<?php

define('DB_TYPE','mysqlt');

Library::load('3rdparty/adodb5/adodb.inc');
Library::load('3rdparty/adodb5/adodb-exceptions.inc');

class Db {
    private static $dbConn;
    
    private static function init() {
        if (!self::$dbConn) {
            $dsn = DB_TYPE . '://' . DB_USERNAME . ':' . rawurlencode(DB_PASSWORD) . '@' . rawurlencode(DB_SERVER) . '/' . DB_DATABASE;
            self::$dbConn = NewADOConnection($dsn);
        }
    }
}