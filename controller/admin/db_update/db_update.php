<?php
class DbUpdateAdminController extends Controller {
	public function exec() {
		if ($this->isPost()) {
			TableVersionsDao::get()->initSchema();
			UserDao::get()->initSchema();
			AdminUserDao::get()->initSchema();
			BasicAuthDao::get()->initSchema();
			SessionDao::get()->initSchema();
			TemplateView::get()->setParam('success',$this->_('success.update_db'));
		}
	}
}