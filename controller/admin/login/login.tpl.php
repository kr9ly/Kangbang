<form class="form-horizontal" method="POST">
	<fieldset>
		<legend>
			<?= $this->_('page.login_message','site.name') ?>
		</legend>
		<? Parts::alert('error',$this->error) ?>
		<div class="control-group">
			<label class="control-label" for="login_id"><?= $this->_('page.login_id') ?>
			</label>
			<div class="controls">
				<? Parts::form('text','login_id','',$this->_('page.login_id')) ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password"><?= $this->_('page.password') ?>
			</label>
			<div class="controls">
				<? Parts::form('password','password','',$this->_('page.password')) ?>
			</div>
		</div>
		<div class="form-actions">
			<? Parts::form('submit','login',$this->_('page.button.login')) ?>
		</div>
	</fieldset>
</form>
<? Parts::frame('admin/login'); ?>