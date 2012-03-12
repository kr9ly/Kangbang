<?php
class AlertCommonParts extends Parts {
	public function exec($text) {
		TemplateView::get()->setParam('text',$text);
	}
}