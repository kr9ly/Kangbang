<?php
class NavigateHelper extends Helper {
	public static function redirect($url) {
		header("HTTP/1.1 302 Found");
		header("Location: " . UrlHelper::http($url));
		exit();
	}

	public static function externalRedirect($url) {
		header("HTTP/1.1 302 Found");
		header("Location: " . $url);
	}
}