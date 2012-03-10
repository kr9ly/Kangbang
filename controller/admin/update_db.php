<?php
class DbUpdateAdminController extends Controller {
	public function exec() {
		UserDao::get()->initSchema();
		AdminUserDao::get()->initSchema();
		BasicAuthDao::get()->initSchema();
		SessionDao::get()->initSchema();
	}
}