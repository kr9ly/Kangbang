<?php
class LabelFormCommonParts extends Parts {
	public function exec($for,$text) {
		TemplateView::get()->setParam('for',$for);
		TemplateView::get()->setParam('text',$text);
	}
}