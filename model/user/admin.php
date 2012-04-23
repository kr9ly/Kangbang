<?php
class AdminUserDao extends Dao {
	public function getAdminUserId($userName, $password) {
		$userId = $this->equalToAdminUserName($userName)->equalToPassword($password)->getOne('admin_user_id');
		return $userId;
	}

	public function getPasswordQuery($val) {
		return hash('sha256',SITE_SALT . $val);
	}

	public function validatePassword($val) {
		if (!preg_match('/[!-~]{6,}/', $val)) {
			return $this->_('error.password_format');
		}
		return;
	}

	public static function unittest() {
		$dao = $this->get();
		$dao->insert(array('user_name' => 'test', 'password' => 'password'));
		$dao->insert(array('user_name' => 'test2', 'password' => 'password'));
		Test::assert(array($dao,'getAdminUserId'), '> 0', 'can register admin user',array('test','password'));
		Test::assert(array($dao,'getAdminUserId'), '== 0', 'can reject invalid user',array('test3','password'));
		Test::assert(array($dao,'getAdminUserId'), '== 0', 'can reject invalid password',array('test','password2'));
	}
}