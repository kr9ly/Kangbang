<?php
class AdminUserDao extends Dao {
	public function isValid($userId, $password) {
		$cnt = $this->equalToAdminUserName($userId)->equalToPassword($password)->select('COUNT(*) cnt');
		return $cnt[0]['cnt'] > 0;
	}
	
	public function getPasswordQuery($val) {
		return sha1($val);
	}
}