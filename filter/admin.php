<?php
class AdminFilter extends Filter {
	public function filter() {
		if (!AdminUserHelper::isAdmin()) {
			NavigateHelper::redirect('admin/login');
		}
	}
}