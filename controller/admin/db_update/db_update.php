<?php
class DbUpdateAdminPage extends Page {
	public function exec() {
		if ($this->isPost()) {
			DbUpdateHelper::update();
			TemplateView::get()->setParam('success',$this->_('success.update_db'));
		}
	}
}