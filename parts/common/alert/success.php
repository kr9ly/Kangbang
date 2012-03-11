<?php
class SuccessAlertCommonParts extends Parts {
	public function exec($text) {
		TemplateView::get()->setParam('text',$text);
	}
}