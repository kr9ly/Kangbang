<?php
class AdminUserListAdminPage extends Page {
	public function exec() {
		TemplateView::get()->setParam('dao',AdminUserDao::get());
	}
}