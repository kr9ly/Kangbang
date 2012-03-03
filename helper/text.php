<?php
class TextHelper extends Helper {
	public static function toCamelCase($text) {
		return Helper::_toCamelCase($text);
	}

	public static function toSnakeCase($text) {
		return Helper::_toSnakeCase($text);
	}
}