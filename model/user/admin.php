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

	public function insertDefaultRecords() {
		$this->insert(array('admin_user_name' => 'admin','password' => 'password'));
	}
}