<form method="POST" action="<?= UrlHelper::http() ?>">
	<div class="modal-header">
		<h3><?= $this->_('page.title') ?></h3>
	</div>
	<div class="modal-body">
		<div class="form-horizontal">
			<fieldset>
				<? Parts::display('common/form/hidden','admin_user_id') ?>
				<div class="control-group">
					<? Parts::display('common/form/label','admin_user_name',$this->_('page.admin_user_name')) ?>
					<div class="controls">
						<? Parts::display('common/form/text','admin_user_name',$this->admin_user_name) ?>
					</div>
				</div>
				<div class="control-group">
					<? Parts::display('common/form/label','password',$this->_('page.password')) ?>
					<div class="controls">
						<? Parts::display('common/form/password','password') ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn modal-close"><?= $this->_('page.button.cancel') ?></a>
		<button class="btn btn-primary" ><?= $this->_('page.button.submit') ?></button>
	</div>
</form>