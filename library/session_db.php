<?php
session_set_save_handler(
	function($savepath, $sessionName) { return true; }
	, function() { return true; }
	, function($sessionId) { return SessionDao::get()->getSession($sessionId); }
	, function($sessionId, $data) { SessionDao::get()->registerSession($sessionId, $data); return true; }
	, function($sessionId) { SessionDao::get()->destroySession($sessionId); return true;}
	, function($lifetime) { SessionDao::get()->expireSessions(); return true; }
);
DateHelper::now();
session_start();