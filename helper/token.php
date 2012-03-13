<?php
class TokenHelper extends Helper {
    public static function generate($key = '') {
        $time = microtime();
        $text = $time . ':' . hash('sha256',$time . SITE_SALT . $key);
        return base64_encode($text);
    }
    
    public static function validate($token,$key = '',$expireTime = 300) {
        $text = base64_decode($token);
        list($time,$hash) = explode(':',$text);
        if (hash('sha256',$time . SITE_SALT . $key) != $hash) {
            return false;
        }
        if ($time > microtime() + $expireTime * 1000000) {
            return false;
        }
        return true;
    }
    
    public static function generateUnique() {
        if (!$_SESSION['token_seed']) {
            $_SESSION['token_seed'] = uniqid();
        }
        return self::generate($_SESSION['token_seed']);
    }
    
    public static function validateUnique() {
        return self::validate($_SESSION['token_seed']);
    }
}