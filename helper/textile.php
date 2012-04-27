<?php
class TextileHelper extends Helper {
	public static function convert($textile) {
		$res = "";

		$lines = explode("\n",$textile);
		$converting = true;
		$blocktype = false;
		foreach ($lines as $line) {
			$length = mb_strlen($line);
			if (strpos($line, 'bq.') === 0) {
				if ($blocktype) {
					$res .= "\n</" . $blocktype . ">\n";
					$blocktype = false;
					$converting = true;
				}
				$blocktype = 'blockquote';
				$line = substr($line, 3);
				$res .= "<blockquote>\n";
			} else if (strpos($line, 'pre.') === 0) {
				if ($blocktype) {
					$res .= "\n</" . $blocktype . ">\n";
					$blocktype = false;
					$converting = true;
				}
				$blocktype = 'pre';
				$line = substr($line, 4);
				$res .= "<pre>\n";
				$converting = false;
			} else if (preg_match("/^p([>=])?(\(+|\)+)?\./u",$line,$matches)) {
				if ($blocktype) {
					$res .= "\n</" . $blocktype . ">\n";
					$blocktype = false;
					$converting = true;
				}
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
			} else if (preg_match("/^h[1-6]\./u",$line,$matches)) {
				if ($blocktype) {
					$res .= "\n</" . $blocktype . ">\n";
					$blocktype = false;
					$converting = true;
				}
				$blocktype = 'h' . $matches[1];
				$line = substr($line,3);
				$res .= '<h' . $matches[1] . '>';
			} else if ($line == "") {
				$res .= "\n";
				if ($blocktype) {
					$res .= '</' . $blocktype . '>';
					$blocktype = false;
					$converting = true;
				}
				continue;
			} else {
				$res .= "<br />\n";
			}
			if (!$converting) {
				$res .= $line;
				continue;
			}
			for ($i=0;$i<$length;$i++) {
				$chr = mb_substr($line, $i, 1);
				$temp = $chr;
				switch ($chr) {
					case '*':
						$offset = mb_strpos(mb_substr($line, $i+1), '*');
						if ($offset !== false) {
							$temp = '<span style="font-weight:bold">' . mb_substr($line, $i+1, $offset) . '</span>';
							$i = $offset;
						}
						break;
					case '_':
						$offset = mb_strpos(mb_substr($line, $i+1), '_');
						if ($offset !== false) {
							$temp = '<span style="font-style:italic">' . mb_substr($line, $i+1, $offset) . '</span>';
							$i = $offset;
						}
						break;
					case '+':
						$offset = mb_strpos(mb_substr($line, $i+1), '+');
						if ($offset !== false) {
							$temp = '<span style="text-decoration:underline">' . mb_substr($line, $i+1, $offset) . '</span>';
							$i = $offset;
						}
						break;
					case '-':
						$offset = mb_strpos(mb_substr($line, $i+1), '-');
						if ($offset !== false) {
							$temp = '<span style="text-decoration:line-through">' . mb_substr($line, $i+1, $offset) . '</span>';
							$i = $offset;
						}
						break;
					case '??':
						$offset = mb_strpos(mb_substr($line, $i+2), '??');
						if ($offset !== false) {
							$temp = '<span style="font-style:italic">' . mb_substr($line, $i+2, $offset) . '</span>';
							$i = $offset+1;
						}
						break;
					case '@':
						$offset = mb_strpos(mb_substr($line, $i+1), '@');
						if ($offset !== false) {
							$temp = '<span style="font-family: monospace">' . mb_substr($line, $i+1, $offset) . '</span>';
							$i = $offset;
						}
						break;
					case '!':
						$offset = mb_strpos(mb_substr($line, $i+1), '!');
						if ($offset !== false && filter_var(mb_substr($line, $i+1, $offset),FILTER_VALIDATE_URL)) {
							$temp = '<img src="' . mb_substr($line, $i+1, $offset) . '"/>';
							$i = $offset;
						}
						break;
					case '"':
						if (preg_match("/[^ ](\"(.+?)\":(.+?))[ $]/u", mb_substr($line,$i==0 ? 0 : $i-1), $matches) && filter_var($matches[3],FILTER_VALIDATE_URL)) {
							$temp = '<a href="' . $matches[3] . '">' . $matches[2] . '</a>';
							$i += mb_strlen($matches[1])-1;
						}
						break;
				}
				$res .= $temp;
			}
		}
		return $res;
	}
}