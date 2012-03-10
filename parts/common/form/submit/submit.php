<?php
class SubmitFormCommonParts extends Parts {
	public function exec($name,$value,$primary = true) {
		TemplateView::get()->setParam('name',$name);
		TemplateView::get()->setParam('value',$value);
		TemplateView::get()->setParam('primary',$primary);
	}
}