<?php
class TextHelper extends Helper {
	public function toCamelCase($text) {
		return Helper::_toCamelCase($text);
	}

	public function toSnakeCase($text) {
		return Helper::_toSnakeCase($text);
	}
}