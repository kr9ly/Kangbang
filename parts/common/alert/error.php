<?php
class ErrorAlertCommonParts extends Parts {
	public function exec($text) {
		TemplateView::get()->setParam('text',$text);
	}
}