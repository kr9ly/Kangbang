<?php
class Error {
	public static function __callStatic($name, $args) {
		$name = 'error' . '/' . $name . '/' . implode('/', $args);
		View::getByPath($name);
		Controller::execByPath($name);
		View::displayView();
	}
}