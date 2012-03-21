<?php
class CheckboxFormCommonParts extends Parts {
	public function exec($name,$label = '',$value = '', $checked = '') {
		TemplateView::get()->setParam('name',$name);
		TemplateView::get()->setParam('label',$label);
		TemplateView::get()->setParam('value',$value);
		TemplateView::get()->setParam('checked',$checked);
	}
}