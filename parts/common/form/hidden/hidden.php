<?php
class HiddenFormCommonParts extends Parts {
	public function exec($name,$value = '') {
		TemplateView::get()->setParam('name',$name);
		TemplateView::get()->setParam('value',$value);
	}
}