<? Parts::display('admin/header'); ?>
<form class="form-horizontal">
	<fieldset>
		<legend>
			<?= $this->_('page.login_message','site.name') ?>
		</legend>
		<div class="control-group">
			<label class="control-label" for="login_id"><?= $this->_('page.login_id') ?>
			</label>
			<div class="controls">
				<input type="text" name="login_id" id="login_id"
					class="input-xlarge" placeholder="<?= $this->_('page.login_id') ?>">
				<p class="help-block">
					<?= $this->_('page.login_id.hint') ?>
				</p>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password"><?= $this->_('page.password') ?>
			</label>
			<div class="controls">
				<input type="text" name="password" id="password"
					class="input-xlarge" placeholder="<?= $this->_('page.password') ?>">
				<p class="help-block">
					<?= $this->_('page.password.hint') ?>
				</p>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary"><?= $this->_('page.button.login') ?></button>
		</div>
	</fieldset>
</form>
<? Parts::display('admin/footer'); ?>