<?php
class LogoutAdminController extends Controller {
	public function exec() {
		AdminUserHelper::logout();
		NavigateHelper::redirect("admin/login");
	}
}