<? Parts::display('admin/header'); ?>
<form method="POST">
	<label><?= $this->_('page.info') ?></label>
	<? Parts::display('common/alert/success',$this->success) ?>
	<button type="submit" class="btn btn-large btn-primary"><?= $this->_('page.button.update') ?></button>
</form>
<? Parts::display('admin/footer'); ?>