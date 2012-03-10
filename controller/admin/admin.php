<?php
class AdminController extends Controller {
	public function exec() {
		if (!AdminUserHelper::isAdmin()) {
			NavigateHelper::redirect('admin/login');
		}
	}
}