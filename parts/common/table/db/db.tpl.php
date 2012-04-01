<div class="pagination pagination-centered">
	<ul>
		<? foreach (range(1, $this->maxPage) as $val) { ?>
		<li<?= $this->page == $val ? ' class="active"' : '' ?>><a href="<? $this->http() ?>?p=<?= $val ?>"><?= $val ?></a></li>
		<? } ?>
	</ul>
</div>
<table id="<?= $this->id ?>" class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
		<? foreach ($this->columns as $val) { ?>
			<td><?= $this->dao->getColumnName($val) ?></td>
		<? } ?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($this->data as $row) { ?>
		<tr>
			<? foreach ($this->columns as $column) { ?>
				<td><?= htmlspecialchars($row[$column]) ?></td>
			<? } ?>
		</tr>
		<? } ?>
	</tbody>
</table>
<div class="pagination pagination-centered">
	<ul>
		<? foreach (range(1, $this->maxPage) as $val) { ?>
		<li<?= $this->page == $val ? ' class="active"' : '' ?>><a href="<? $this->http() ?>?p=<?= $val ?>"><?= $val ?></a></li>
		<? } ?>
	</ul>
</div>