<?php
class Library {
    private static $loadedPaths = array();
    
    public static function load($path) {
        if (!array_key_exists($path,self::$loadedPaths)) {
            require BASE_PATH . '/library/' . $path . '.php';
            self::$loadedPaths[$path] = true;
        }
    }
}