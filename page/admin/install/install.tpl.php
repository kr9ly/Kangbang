<form class="form-horizontal" method="POST">
	<fieldset>
		<legend>
			<?= $this->_('page.install_message') ?>
		</legend>
		<? Parts::display('common/alert/error',$this->errors['message']) ?>
		<div class="control-group">
			<label class="control-label" for="development_mode"><?= $this->_('page.development_mode') ?>
			</label>
			<div class="controls">
				<? Parts::form('select','development_mode', $this->development_mode, 1) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_type"><?= $this->_('page.db_type') ?>
			</label>
			<div class="controls">
				<? Parts::form('select','db_type', $this->db_type) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_server'] ? ' error' : '' ?>">
			<label class="control-label" for="db_server"><?= $this->_('page.db_server') ?>
			</label>
			<div class="controls">
				<? Parts::form('text','db_server','',$this->_('page.db_server.placeholder'),$this->errors['db_server']) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_username'] ? ' error' : '' ?>">
			<label class="control-label" for="db_username"><?= $this->_('page.db_username') ?>
			</label>
			<div class="controls">
				<? Parts::form('text','db_username','',$this->_('page.db_username.placeholder'),$this->errors['db_username']) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_password"><?= $this->_('page.db_password') ?>
			</label>
			<div class="controls">
				<? Parts::form('password','db_password','',$this->_('page.db_password.placeholder')) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_database'] ? ' error' : '' ?>">
			<label class="control-label" for="db_database"><?= $this->_('page.db_database') ?>
			</label>
			<div class="controls">
				<? Parts::form('text','db_database','',$this->_('page.db_database.placeholder'),$this->errors['db_database']) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="session_type"><?= $this->_('page.session_type') ?>
			</label>
			<div class="controls">
				<? Parts::form('select','session_type', $this->session_type) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="cache_type"><?= $this->_('page.cache_type') ?>
			</label>
			<div class="controls">
				<? Parts::form('select','cache_type', $this->cache_type) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="default_lang"><?= $this->_('page.default_lang') ?>
			</label>
			<div class="controls">
				<? Parts::form('select','default_lang', $this->default_lang) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="default_timezone"><?= $this->_('page.default_timezone') ?>
			</label>
			<div class="controls">
				<? Parts::form('select','default_timezone', $this->default_timezone) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['admin_user_name'] ? ' error' : '' ?>">
			<label class="control-label" for="admin_username"><?= $this->_('page.admin_username') ?>
			</label>
			<div class="controls">
				<? Parts::form('text','admin_username','',$this->_('page.admin_username.placeholder'),$this->errors['admin_user_name']) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['password'] ? ' error' : '' ?>">
			<label class="control-label" for="admin_pass"><?= $this->_('page.admin_pass') ?>
			</label>
			<div class="controls">
				<? Parts::form('text','admin_pass','',$this->_('page.admin_pass.placeholder'),$this->errors['password']) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="enable_https"><?= $this->_('page.enable_https') ?>
			</label>
			<div class="controls">
				<? Parts::form('checkbox','enable_https',$this->_('page.enable_https.label'),'1',false) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_initialize"><?= $this->_('page.db_initialize') ?>
			</label>
			<div class="controls">
				<? Parts::form('checkbox','db_initialize',$this->_('page.db_initialize.label'),'1',true) ?>
			</div>
		</div>
		<div class="form-actions">
			<? Parts::form('submit','install',$this->_('page.button.install')) ?>
		</div>
	</fieldset>
</form>
<? Parts::frame('admin/login'); ?>