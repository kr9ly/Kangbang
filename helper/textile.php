<?php
class TextileHelper extends Helper {
	private static $converting = true;

	public static function convert($textile) {
		$res = "";

		$lines = explode("\n",$textile);
		$converting = true;
		$blocktype = false;
		foreach ($lines as $line) {
			$length = mb_strlen($line);
			if ($line == "") {
				$res .= "\n";
				if ($blocktype) {
					$res .= '</' . $blocktype . '>';
					$blocktype = false;
				}
				continue;
			}
			if (!$blocktype) {
				$res .= "\n";
				if (strpos($line, 'bq.') === 0) {
					$blocktype = 'blockquote';
					$line = substr($line, 3);
					$res .= "<blockquote>\n";
				} else if (preg_match("/^p([>=])?(\(+|\)+)?\./u",$line,$matches)) {
					$blocktype = 'p';
					if ($matches[1] == '>') {
						$style = 'text-align:right;';
					} else if ($matches[1] == '=') {
						$style = 'text-align:center;';
					}
					if (strpos($matches[1], '(') === 0) {
						$margin = strlen($mathces[1]);
						$style .= 'margin-left:' . $margin . 'em';
					} else if (strpos($matches[1], ')') === 0) {
						$margin = strlen($mathces[1]);
						$style .= 'margin-right:' . $margin . 'em';
					}
					$block = '<p' . ($style ? ' style="' . $style . '"' : '') . '>';
					$line = substr($line,strlen($block));
					$res .= $block . "\n";
				}
			} else {
				$res .= "<br />\n";
			}
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