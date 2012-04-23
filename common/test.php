<?php

class Test {
	private static $results = array();

	public static function assert($function, $assertion, $message, $args = array()) {
		$trace = debug_backtrace();

		if (!$results[$trace[1]['class']]) {
			$results[$trace[1]['class']] = array();
		}

		try {
			$val = call_user_func_array($function, $args);
			$res = eval('return $val ' . $assertion);
			if (!$res) {
				throw new Exception();
			}
			$results[$trace[1]['class']][$message] = true;
		} catch (Exception $e) {
			$results[$trace[1]['class']][$message] = false;
		}
	}
}
