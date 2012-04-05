<?php
class AdminController extends Controller {
	public function exec() {
		$environments = array(
			$this->_('env.development_mode') => DEVELOPMENT_MODE ? 'ON' : OFF
			,$this->_('env.db_type') => DB_TYPE
			,$this->_('env.db_server') => DB_SERVER
			,$this->_('env.db_username') => DB_USERNAME
			,$this->_('env.db_database') => DB_DATABASE
			,$this->_('env.session_type') => SESSION_TYPE
			,$this->_('env.cache_type') => CACHE_TYPE
			,$this->_('env.default_lang') => DEFAULT_LANG
			,$this->_('env.default_timezone') => DEFAULT_TIMEZONE
			,$this->_('env.base_url') => BASE_URL
			,$this->_('env.base_ssl_url') => BASE_SSL_URL
		);
		TemplateView::get()->setParam('environments',$environments);
	}
}