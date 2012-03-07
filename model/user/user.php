<?php
class UserDao extends Dao {
	public function newUser($userName) {
		$this->insert(array('user_name' => $userName));
		return Db::getInsertId();
	}
}