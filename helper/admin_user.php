<?php
class AdminUserHelper extends Helper {
	public static function isAdmin() {
		if ($_SESSION['admin_user']) {
			return true;
		}
		return false;
	}

	public static function login($userName,$password) {
		$userId = AdminUserDao::get()->getAdminUserId($userName, $password);
		if ($userId) {
			$_SESSION['admin_user'] = $userId;
			return true;
		}
		return false;
	}

	public static function logout() {
		unset($_SESSION['admin_user']);
	}
}