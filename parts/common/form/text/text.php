<?php
class TextFormCommonParts extends Parts {
	public function exec($name,$value = '',$placeholder = '') {
		TemplateView::get()->setParam('name',$name);
		TemplateView::get()->setParam('value',$value);
		TemplateView::get()->setParam('placeholder',$placeholder);
	}
}