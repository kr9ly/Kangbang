<?php
class ModalCommonParts extends Parts {
	public function exec($selector,$action) {
		TemplateView::get()->setParam('selector',$selector);
		TemplateView::get()->setParam('action',$action);
	}
}