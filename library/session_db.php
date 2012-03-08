<?php

class SessionDbHandler {
	public function open($savepath, $sessionName) { return true; }
	public function close() { return true; }
	public function read($sessionId) { return SessionDao::get()->getSession($sessionId); }
	public function write($sessionId, $data) { SessionDao::get()->registerSession($sessionId, $data); return true; }
	public function destroy($sessionId) { SessionDao::get()->destroySession($sessionId); return true;}
	public function gc($lifetime) { SessionDao::get()->expireSessions(); return true; }

	public function __destruct() {
		session_write_close();
	}
}

$s = new SessionDbHandler();

session_set_save_handler(
	  array($s, 'open')
	, array($s, 'close')
	, array($s, 'read')
	, array($s, 'write')
	, array($s, 'destroy')
	, array($s, 'gc')
);
session_start();