<?php
class TextHelper extends Helper {
	public static function toCamelCase($text) {
		return Helper::_toCamelCase($text);
	}

	public static function toSnakeCase($text) {
		return Helper::_toSnakeCase($text);
	}

	public static function unittest() {
		Test::assert('TextHelper::toCamelCase', ' == "FooBar"',  'can convert to camelcase',array('foo_bar'));
		Test::assert('TextHelper::toCamelCase', ' == "foo_bar"',  'can convert to snakecase',array('FooBar'));
	}
}