<form method="POST">
	<label><?= $this->_('page.info') ?></label>
	<? Parts::alert('success',$this->success) ?>
	<button type="submit" class="btn btn-large btn-primary"><?= $this->_('page.button.clear') ?></button>
</form>

<? Parts::frame('admin'); ?>