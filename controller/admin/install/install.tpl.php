<? Parts::display('admin/login_header'); ?>
<form class="form-horizontal" method="POST">
	<fieldset>
		<legend>
			<?= $this->_('page.install_message') ?>
		</legend>
		<? Parts::display('common/alert/error',$this->error) ?>
		<div class="control-group">
			<label class="control-label" for="development_mode"><?= $this->_('page.development_mode') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/select','development_mode', $this->development_mode, 1) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_type"><?= $this->_('page.db_type') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/select','db_type', $this->db_type) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_server'] ? ' error' : '' ?>">
			<label class="control-label" for="db_server"><?= $this->_('page.db_server') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/text','db_server','',$this->_('page.db_server.placeholder'),$this->errors['db_server']) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_username'] ? ' error' : '' ?>">
			<label class="control-label" for="db_username"><?= $this->_('page.db_username') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/text','db_username','',$this->_('page.db_username.placeholder'),$this->errors['db_username']) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_password"><?= $this->_('page.db_password') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/password','db_password','',$this->_('page.db_password.placeholder')) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="session_type"><?= $this->_('page.session_type') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/select','session_type', $this->session_type) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="cache_type"><?= $this->_('page.cache_type') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/select','cache_type', $this->cache_type) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="default_lang"><?= $this->_('page.default_lang') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/select','default_lang', $this->default_lang) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="default_timezone"><?= $this->_('page.default_timezone') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/select','default_timezone', $this->default_timezone) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['base_url'] ? ' error' : '' ?>">
			<label class="control-label" for="base_url"><?= $this->_('page.base_url') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/text','base_url','http://localhost/','',$this->errors['base_url']) ?>
			</div>
		</div>
		<div class="control-group<?= $this->errors['base_ssl_url'] ? ' error' : '' ?>">
			<label class="control-label" for="base_ssl_url"><?= $this->_('page.base_ssl_url') ?>
			</label>
			<div class="controls">
				<? Parts::display('common/form/text','base_ssl_url','https://localhost/','',$this->errors['base_ssl_url']) ?>
			</div>
		</div>
		<div class="form-actions">
			<? Parts::display('common/form/submit','install',$this->_('page.button.install')) ?>
		</div>
	</fieldset>
</form>
<? Parts::display('admin/footer'); ?>