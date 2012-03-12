<?php
class VerticalTableCommonParts extends Parts {
	public function exec($data) {
		TemplateView::get()->setParam('data',$data);
	}
}