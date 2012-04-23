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

	public static function validateUnique($token, $expireTime = 300) {
		return self::validate($token, $_SESSION['token_seed'], $expireTime);
	}

	public static function unittest() {
		$foo = TokenHelper::generate('foo');
		Test::assert("TokenHelper::validate", " === true", "can validate token",array($foo,'foo'));
		Test::assert("TokenHelper::validate", " === true", "reject invalid token",array($foo,'bar'));
		Test::assert("TokenHelper::validate", " === false", "can expire token",array($foo,'foo',0));
		$unique = TokenHelper::generateUnique();
		Test::assert("TokenHelper::validateUnique", " === true", "can validate unique token",array($unique));
		Test::assert("TokenHelper::validateUnique", " === false", "can expire unique token",array($unique,0));
	}
}