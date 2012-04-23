<?php
class LogoutAdminPage extends Page {
	public function exec() {
		AdminUserHelper::logout();
		NavigateHelper::redirect("admin/login");
	}
}