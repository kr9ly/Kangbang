<?php
class TextileHelper extends Helper {
	private static $converting = true;

	public static function convert($textile) {
		$res = "";

		$lines = explode("\n",$textile);
		$converting = true;
		$inblock = false;
		foreach ($lines as $line) {
			$length = mb_strlen($textile);
			if ($line == "") {
				$res .= "\n";
				if ($inblock) {
					$res .= '</p>';
					$inblock = false;
				}
				continue;
			}
			if (!$inblock) {
				if (preg_match("/^p[>=\)]\./u",$line,$matches)) {

				}
			} else {
				$res .= "<br />";
			}
			$res .= "\n";
			for ($i=0;$i<$length;$i++) {
				$chr = mb_substr($line, $i, 1);
				$temp = $chr;
				switch ($chr) {
					case '*':
						if (preg_match("/^\*(.+?)\*/u", mb_substr($line, $i), $matches)) {
							$temp = '<span style="font-weight:bold">' . $matches[1] . '</span>';
							$i += mb_strlen($matches[1])-1+2;
						}
						break;
					case '_':
						if (preg_match("/^_(.+?)_/u", mb_substr($line, $i), $matches)) {
							$temp = '<span style="font-style:italic">' . $matches[1] . '</span>';
							$i += mb_strlen($matches[1])-1+2;
						}
						break;
					case '+':
						if (preg_match("/^\+(.+?)\+/u", mb_substr($line, $i), $matches)) {
							$temp = '<span style="text-decoration:underline">' . $matches[1] . '</span>';
							$i += mb_strlen($matches[1])-1+2;
						}
						break;
					case '-':
						if (preg_match("/^-(.+?)-/u", mb_substr($line, $i), $matches)) {
							$temp = '<span style="text-decoration:line-through">' . $matches[1] . '</span>';
							$i += mb_strlen($matches[1])-1+2;
						}
						break;
					case '??':
						if (preg_match("/^\?\?(.+?)\?\?/u", mb_substr($line, $i), $matches)) {
							$temp = '<span style="font-style:italic">' . $matches[1] . '</span>';
							$i += mb_strlen($matches[1])-1+4;
						}
						break;
					case '@':
						if (preg_match("/^@(.+?)@/u", mb_substr($line, $i), $matches)) {
							$temp = '<span style="font-family: monospace">' . $matches[1] . '</span>';
							$i += mb_strlen($matches[1])-1+2;
						}
						break;
				}
				$res .= $temp;
			}
		}
		return $res;
	}
}