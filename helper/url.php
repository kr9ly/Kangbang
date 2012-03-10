<?php
class UrlHelper extends Helper {
	public static function http($url) {
		if (strpos($url,'/') === 0) {
			$url = substr($url,1);
		}
		return BASE_URL . $url;
	}

	public static function https($url) {
		if (strpos($url,'/') === 0) {
			$url = substr($url,1);
		}
		return BASE_SSL_URL . $url;
	}
}