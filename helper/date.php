<?php
class DateHelper extends Helper {
	public static function now($format = 'Y-m-d H:i:s') {
		return self::date('now',$format);
	}

	public static function date($time = 'now', $format = 'Y-m-d H:i:s') {
		$date = new DateTime($time);
		return $date->format($format);
	}
}