<?php
class TemplateHelper extends Helper {
	private static $frames = array();

	public static function convert($html) {
		self::$frames = array();
		$html = str_replace("\t","",$html);
		$html = preg_replace_callback('/_{(.+)}/u', "TemplateHelper::convertLang", $html);
		$html = preg_replace('/${(.+)}/u', '<?= $this->$1 ?>', $html);
		$html = preg_replace_callback("/<(\/)?([a-z]+):(.+)>/u", "TemplateHelper::convertTag", $html);
		return $html;
	}

	private static function convertLang($matches) {
		$args = explode(',',$matches[1]);
		$args = array_map(function($arg){
			if (strpos($arg,'$') === 0) {
				return '$this->' . $arg;
			}
			return '"' . $arg . '"';
		}, $args);
		return sprintf('<?= $this->_(%s) ?>',implode(',',$args));
	}

	private static function convertTag($matches) {
		$close = $matches[1] == '/';
		$func = trim($matches[2]);
		$args = explode(',',preg_replace("/\/$/u","",$matches[3]));
		$args = array_map(function($arg){
			$arg = trim($arg);
			if (strpos($arg,'$') === 0) {
				return '$this->' . substr($arg,1);
			} else if (strpos($arg,'_') === 0) {
				return '$this->_("' . substr($arg,1) . '")';
			} else if ($arg == 'true' || $arg == 'false') {
				return $arg;
			}
			return '"' . $arg . '"';
		}, $args);

		if (strpos($func,"parts") === 0) {
			return sprintf('<? Parts::display(%s) ?>',implode(',',$args));
		} else if (strpos($func,"frame") === 0) {
			if ($close && self::$frames) {
				return sprintf('<? Parts::frame(%s) ?>',implode(',',array_shift(self::$frames)));
			} else if (!$close) {
				self::$frames[] = $args;
				return '<? ob_start() ?>';
			}
		} else {
			return sprintf('<? Parts::%s(%s) ?>',$func,implode(',',$args));
		}
	}
}