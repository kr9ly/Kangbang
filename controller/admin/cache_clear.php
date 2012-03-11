<?php
class ClearCacheAdminController extends Controller {
	public function exec() {
		if ($this->isPost()) {
			Cache::clear();
			TemplateView::get()->setParam('success',$this->_('success.clear_cache'));
		}
	}
}