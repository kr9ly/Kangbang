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
			if (!$_POST['db_database']) {
				$errors['db_database'] = $this->_('error.require_db_database');
			}
			$auerrors = AdminUserDao::get()->validate(array('admin_user_name' => $_POST['admin_username'],'password' => $_POST['admin_pass']));
			if ($auerrors) {
				$errors = array_merge($errors,$auerrors);
			}
			if (count($errors) == 0) {
				define('DB_TYPE',$_POST['db_type']);
				define('DB_SERVER',$_POST['db_server']);
				define('DB_USERNAME',$_POST['db_username']);
				define('DB_PASSWORD',$_POST['db_password']);
				define('DB_DATABASE',$_POST['db_database']);

				if (!Db::init()) {
					$errors['message'] = $this->_('error.db_connect_failure');
				} else if (!is_writable(BASE_PATH . '/config/')) {
					$errors['message'] = $this->_('error.config_notwritable');
				} else {
					$confs = $this->getSiteConfs();
					file_put_contents(BASE_PATH . '/config/site.php',$confs);
					DbUpdateHelper::update($_POST['db_initialize']);
					AdminUserDao::get()->insert(array('admin_user_name' => $_POST['admin_username'],'password' => $_POST['admin_pass']));

					Library::load('cache_' . $_POST['cache_type']);
					Cache::clear();
					NavigateHelper::redirect('/');
				}
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

	private function getSiteConfs() {
		$development_mode = $_POST['development_mode'] ? 'true' : 'false';
		$db_type = $_POST['db_type'];
		$db_server = $_POST['db_server'];
		$db_username = $_POST['db_username'];
		$db_password = $_POST['db_password'];
		$db_database = $_POST['db_database'];
		$session_type = $_POST['session_type'];
		$cache_type = $_POST['cache_type'];
		$default_lang = $_POST['default_lang'];
		$default_timezone = $_POST['default_timezone'];
		$enable_https = $_POST['enable_https'] ? 'true' : 'false';
		$site_salt = uniqid();
		define('SITE_SALT',$site_salt);
		return <<< EOF
<?php
define('DEVELOPMENT_MODE',$development_mode);
define('DB_TYPE','$db_type');
define('DB_SERVER','$db_server');
define('DB_USERNAME','$db_username');
define('DB_PASSWORD','$db_password');
define('DB_DATABASE','$db_database');
define('SESSION_TYPE','$session_type');
define('CACHE_TYPE','$cache_type');
define('DEFAULT_LANG','$default_lang');
define('DEFAULT_TIMEZONE','$default_timezone');
define('ENABLE_HTTPS',$enable_https);
define('SITE_SALT','$site_salt');
EOF;
	}
}