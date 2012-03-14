<?php
class InstallAdminController extends Controller {
	public function exec() {
		if (!INSTALL_MODE) {
			die('access denied.');
		}
		$errors = array();
		if ($this->isPost()) {
			if (!$_POST['db_server']) {
				$errors['db_server'] = $this->_('error.require_db_server');
			}
			if (!$_POST['db_username']) {
				$errors['db_username'] = $this->_('error.require_db_username');
			}
			if (!$_POST['base_url']) {
				$errors['base_url'] = $this->_('error.require_base_url');
			}
			if (!$_POST['base_ssl_url']) {
				$errors['base_ssl_url'] = $this->_('error.require_base_ssl_url');
			}
		}
		$t = TemplateView::get();
		$t->setParam('development_mode',array($this->_('page.development_mode.off'), $this->_('page.development_mode.on')));
		$t->setParam('db_type',array(
			'mysqlt' => 'mysql'
		));
		$t->setParam('session_type',array('db' => 'db'));
		$t->setParam('cache_type',array('apc' => 'apc'));
		$t->setParam('default_lang',array('ja_JP'=>'ja_JP'));
		$t->setParam('default_timezone',array('Asia/Tokyo'=>'Asia/Tokyo'));
		$t->setParam('errors',$errors);
	}
}