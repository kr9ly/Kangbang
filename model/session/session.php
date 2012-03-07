<?php
class SessionDao extends Dao {
	public function getSession($sessionId) {
		$res = $this->equalToSessionId($sessionId)->select('session_data');
		return $res ? $res[0]['session_data'] : '';
	}

	public function registerSession($sessionId, $sessionData) {
		$this->insert(array('session_id' => $sessionId, 'session_data' => $sessionData, 'expire_date' => DateHelper::date('+' . session_cache_expire() . 'minutes')));
	}

	public function destroySession($sessionId) {
		$this->equalToSessionId($sessionId)->delete();
	}

	public function expireSessions() {
		$this->lessThanExpireDate(DateHelper::now());
	}
}