<?php
class IndexPage extends Page {
	public function exec() {
		$text = <<< EOF
h1.そのままブロック
h2.コード
code:php.<?php

class SessionDbHandler {
	public function open(\$savepath, \$sessionName) { return true; }
	public function close() { return true; }
	public function read(\$sessionId) { return SessionDao::get()->getSession(\$sessionId); }
	public function write(\$sessionId, \$data) { SessionDao::get()->registerSession(\$sessionId, \$data); return true; }
	public function destroy(\$sessionId) { SessionDao::get()->destroySession(\$sessionId); return true;}
	public function gc(\$lifetime) { SessionDao::get()->expireSessions(); return true; }

	public function __destruct() {
		session_write_close();
	}
}

\$s = new SessionDbHandler();

session_set_save_handler(
	  array(\$s, 'open')
	, array(\$s, 'close')
	, array(\$s, 'read')
	, array(\$s, 'write')
	, array(\$s, 'destroy')
	, array(\$s, 'gc')
);
session_start();
code.

{{toc}}

h2.引用
bq.オッケーオッケーそのまま表示
そのまま	ああああ
てｓｔ

h2.そのまま
pre.
これはそのまま表示されまーす
表示！表示！+変換しまーす+


h1.色々

h2.インデント
p(. 左インデント(1em)
p((. 左インデント(2em)
p((((. 左インデント(4em)
b
aa

c
h2.インデント２
p). 右インデント(1em)
p)). 右インデント(2em)
p)))). 右インデント(4em)

h4.箇条書き
* list1
* list2
** list2.1
** list2.2
*** list2.2.1
* list3

h5.箇条書き（番号付き）
# list1
# list2
## list2.1
## list2.2
### list2.2.1
# list3
h2.テーブル
|_. name|_. age|_. sex|
|tarou|24|male|
|hanako|20|female|

|_. セルの位置属性とその効果|
|<. align left|
|>. align right|
|=. align center|
|<>. align justify|
|^. valign top|
|~. valign bottom|

|/3. rowspan 3|row1|
|row2|
|row3|

|\\3. colspan 3|
|col1|col2|col3|

!http://www.google.co.jp/intl/ja_jp/images/logo.gif!
EOF;
		$text = TextileHelper::convert($text);
		TemplateView::get()->setParam('textile',$text);
	}
}