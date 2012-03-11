<table class="table table-striped table-bordered">
	<tbody>
		<? foreach ($this->_data as $key => $val) { ?>
		<tr><td><?= htmlspecialchars($key) ?></td><td><?= htmlspecialchars($val) ?></td></tr>
		<? } ?>
	</tbody>
</table>