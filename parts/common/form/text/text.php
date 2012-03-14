<?php
class TextFormCommonParts extends Parts {
	public function exec($name,$value = '',$placeholder = '', $helptext = '') {
		TemplateView::get()->setParam('name',$name);
		TemplateView::get()->setParam('value',$value);
		TemplateView::get()->setParam('placeholder',$placeholder);
		TemplateView::get()->setParam('helptext',$helptext);
	}
}