<?php
Library::load('3rdparty/geshi/geshi');
class TextileHelper extends Helper {
	public static function convert($textile) {
		$res = "";

		$lines = explode("\n",$textile);
		list($blocktype, $converting, $nestlevel) = array(false, true, 0);
		foreach ($lines as $line) {
			$line = rtrim($line);
			$newBlock = false;
			$cont = false;
			switch(true) {
				case strpos($line, 'bq.') === 0:
					$newBlock = 'blockquote';
					break;
				case strpos($line, 'pre.') === 0:
					$newBlock = 'pre';
					break;
				case strpos($line, 'code.') === 0:
					$res .= '</code>' . "\n";
					$blocktype = false;
					$converting = true;
					continue 2;
				case preg_match("/^code:([a-z_]+)\./u",$line,$matches):
					$newBlock = 'code';
					break;
				case preg_match("/^p([>=])?([\(\)]+)?\./u",$line,$matches):
					$newBlock = 'p';
					break;
				case preg_match("/^h([1-6])\./u",$line,$matches):
					$newBlock = 'h' . $matches[1];
					break;
				case preg_match("/^\|.+\|$/u", $line):
					$newBlock = 'table';
					break;
				case preg_match("/^(\*+)/u",$line,$matches):
					$newBlock = 'ul';
					break;
				case preg_match("/^(#+)/u",$line,$matches):
					$newBlock = 'ol';
					break;
				case $line == "":
					switch ($blocktype) {
						case 'code':
							$newBlock = $blocktype;
							$cont = true;
							break;
						default:
							$newBlock = 'p';
							$matches = false;
							break;
					}
					break;
				default:
					switch ($blocktype) {
						case 'pre':
						case 'code':
						case 'blockquote':
							$newBlock = $blocktype;
							break;
						default:
							$newBlock = 'p';
							break;
					}
					$cont = true;
					break;
			}
			if ($blocktype && ((!$cont && !$line) || !(($cont || $newBlock == 'ul' || $newBlock == 'table' || $newBlock == 'ol') && $blocktype == $newBlock))) {
				$res .= implode('',array_fill(0, $nestlevel ? $nestlevel : 1, '</' . $blocktype . '>')) . "\n";
				list($blocktype, $converting, $nestlevel) = array(false, true, 0);
			} else {
				switch ($blocktype) {
					case 'code':
						$res .= "\n";
						break;
					case 'ol':
					case 'ul':
					case false:
						break;
					case 'table':
						$res .= "</tr>\n";
						break;
					default:
						$res .= "<br />\n";
						break;
				}
			}
			switch ($newBlock) {
				case 'blockquote':
					if ($blocktype == 'blockquote') {
						break;
					}
					$line = substr($line, 3);
					$res .= "<blockquote>\n";
					break;
				case 'pre':
					if ($blocktype == 'pre') {
						break;
					}
					$line = substr($line, 4);
					$res .= "<pre>\n";
					$converting = false;
					break;
				case 'code':
					if ($blocktype == 'code') {
						break;
					}
					$lang = $matches[1];
					$line = substr($line, strlen($matches[0]));
					$res .= '<code class="' . $lang . '">' . "\n";
					$converting = false;
					break;
				case 'p':
					if ($blocktype == 'p') {
						break;
					}
					$style = '';
					if ($matches) {
						if ($matches[1] == '>') {
							$style = 'text-align:right;';
						} else if ($matches[1] == '=') {
							$style = 'text-align:center;';
						}
						if (strpos($matches[2], '(') === 0) {
							$margin = strlen($matches[2]);
							$style .= 'margin-left:' . $margin . 'em;';
						} else if (strpos($matches[2], ')') === 0) {
							$margin = strlen($matches[2]);
							$style .= 'margin-right:' . $margin . 'em;';
						}
					}
					$block = '<p' . ($style ? ' style="' . $style . '"' : '') . '>';
					$line = substr($line,strlen($matches[0]));
					$res .= $block;
					break;
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':
					$line = substr($line,3);
					$res .= '<h' . $matches[1] . '>';
					break;
				case 'table':
					if ($blocktype == "table") {
						$res .= '<tr>';
					} else {
						$blocktype = 'table';
						$res .= "<table>\n<tr>";
					}
					break;
				case 'ul':
				case 'ol':
					if ($blocktype == $newBlock) {
						if (strlen($matches[1]) > $nestlevel) {
							$nestlevel++;
							$res .= "\n<" . $blocktype . ">\n<li>";
						} else if (strlen($matches[1]) < $nestlevel) {
							$res .= "</li>\n</li>\n" . implode('',array_fill(0, $nestlevel - strlen($matches[1]), "</" . $blocktype . ">")) . "\n<li>";
							$nestlevel = strlen($matches[1]);
						} else {
							$res .= "</li>\n<li>";
						}
						$line = substr($line,$nestlevel);
					} else {
						$nestlevel = 1;
						$line = substr($line,1);
						$res .= "<" . $newBlock . ">\n<li>";
					}
					break;
			}
			$blocktype = $newBlock;
			if (!$converting) {
				switch ($blocktype) {
					case 'code':
						$res .= $line;
						break;
					default:
						$res .= htmlspecialchars($line);
				}
				continue;
			}
			$length = mb_strlen($line);
			for ($i=0;$i<$length;$i++) {
				$chr = mb_substr($line, $i, 1);
				$temp = htmlspecialchars($chr);
				switch ($chr) {
					case '*':
						$offset = mb_strpos(mb_substr($line, $i+1), '*');
						if ($offset !== false) {
							$temp = '<span style="font-weight:bold">' . htmlspecialchars(mb_substr($line, $i+1, $offset)) . '</span>';
							$i += $offset+1;
						}
						break;
					case '_':
						$offset = mb_strpos(mb_substr($line, $i+1), '_');
						if ($offset !== false) {
							$temp = '<span style="font-style:italic">' . htmlspecialchars(mb_substr($line, $i+1, $offset)) . '</span>';
							$i += $offset+1;
						}
						break;
					case '+':
						$offset = mb_strpos(mb_substr($line, $i+1), '+');
						if ($offset !== false) {
							$temp = '<span style="text-decoration:underline">' . htmlspecialchars(mb_substr($line, $i+1, $offset)) . '</span>';
							$i += $offset+1;
						}
						break;
					case '-':
						$offset = mb_strpos(mb_substr($line, $i+1), '-');
						if ($offset !== false) {
							$temp = '<span style="text-decoration:line-through">' . htmlspecialchars(mb_substr($line, $i+1, $offset)) . '</span>';
							$i += $offset+1;
						}
						break;
					case '??':
						$offset = mb_strpos(mb_substr($line, $i+2), '??');
						if ($offset !== false) {
							$temp = '<span style="font-style:italic">' . htmlspecialchars(mb_substr($line, $i+2, $offset)) . '</span>';
							$i += $offset+2;
						}
						break;
					case '@':
						$offset = mb_strpos(mb_substr($line, $i+1), '@');
						if ($offset !== false) {
							$temp = '<span style="font-family:monospace">' . htmlspecialchars(mb_substr($line, $i+1, $offset)) . '</span>';
							$i += $offset+1;
						}
						break;
					case '!':
						$offset = mb_strpos(mb_substr($line, $i+1), '!');
						if ($offset !== false && filter_var(mb_substr($line, $i+1, $offset),FILTER_VALIDATE_URL)) {
							$temp = '<img src="' . htmlspecialchars(mb_substr($line, $i+1, $offset)) . '"/>';
							$i += $offset+1;
						}
						break;
					case '"':
						if (preg_match("/[^ ](\"(.+?)\":(.+?))[ $]/u", mb_substr($line,$i==0 ? 0 : $i-1), $matches) && filter_var($matches[3],FILTER_VALIDATE_URL)) {
							$temp = '<a href="' . $matches[3] . '">' . htmlspecialchars($matches[2]) . '</a>';
							$i += mb_strlen($matches[1]);
						}
						break;
					case "|":
						if ($blocktype == "table") {
							if ($i > 0) {
								$res .= '</' . $tTag . '>';
							}
							if (mb_strpos($line, '|', $i+1) === false) {
								$i = $length;
								$temp = '';
								break;
							}
							$tTag = 'td';
							$tStyle = '';
							$tOptions = '';
							if (preg_match("/^(_?[0-9\\\\\/^~<>=]*)\./u", mb_substr($line, $i+1), $matches)) {
								if (strpos($matches[1], '_') !== false) {
									$tTag = 'th';
								}
								if (preg_match("/\\\\([0-9])/u", $matches[1], $number)) {
									$tOptions .= ' colspan="' . $number[1] . '"';
								}
								if (preg_match("/\/([0-9])/u", $matches[1], $number)) {
									$tOptions .= ' rowspan="' . $number[1] . '"';
								}
								if (strpos($matches[1],'<') !== false) {
									$tStyle .= 'text-align:left;';
								} else if (strpos($matches[1],'>') !== false) {
									$tStyle .= 'text-align:right;';
								} else if (strpos($matches[1],'=') !== false) {
									$tStyle .= 'text-align:center;';
								}
								if (strpos($matches[1],'^') !== false) {
									$tStyle .= 'vertical-align:top;';
								} else if (strpos($matches[1],'~') !== false) {
									$tStyle .= 'vertical-align:bottom;';
								}
								$i += mb_strlen($matches[1])+1;
							}
							$temp = '<' . $tTag . $tOptions . ($tStyle ? ' style="' . $tStyle . '"' : '') . '>';
						}
						break;
				}
				$res .= $temp;
			}
		}
		$res .= implode('',array_fill(0, $nestlevel ? $nestlevel : 1, '</' . $blocktype . '>')) . "\n";

		$res = preg_replace_callback("/<code class=\"(.+)\">(.+)<\/code>/smu", "TextileHelper::convertCode", $res);

		return $res;
	}

	private static function convertCode($matches) {
		$geshi = new GeSHi($matches[2],$matches[1]);
		return $geshi->parse_code();
	}
}