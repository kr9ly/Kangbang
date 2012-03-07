<?php
class BasicAuthDao extends Dao {
	public function registerPassword($userId, $password) {
		$this->insert(array('user_id' => $userId, 'password' => $password));
	}

	public function isValid($userId, $password) {
		$cnt = $this->joinUserOnKey(user_id)->equalToUserName($userId)->equalToPassword($password)->select('COUNT(*) cnt');
		return $cnt[0]['cnt'] > 0;
	}

	public function getPasswordQuery($val) {
		return sha1($val);
	}
}