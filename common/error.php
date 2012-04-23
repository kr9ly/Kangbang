<?php
class Error {
	public static function __callStatic($name, $args) {
		$name = 'error' . '/' . $name . '/' . implode('/', $args);
		View::getByPath($name);
		Page::execByPath($name);
		View::displayView();
	}
}