<?php
class IndexController extends Controller {
	public function exec() {
		$view = TemplateView::get();
		//UserDao::get()->initSchema();
		//AdminUserDao::get()->initSchema();
		//BasicAuthDao::get()->initSchema();
		//Db::begin();
		//$userId = UserDao::get()->newUser('test', 'password');
		//BasicAuthDao::get()->registerPassword($userId, 'password');
		//Db::commit();
		var_dump(BasicAuthDao::get()->validate(array('password' => 'abcefg12')));
	}
}