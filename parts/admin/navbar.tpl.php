<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<div class="nav-collapse">
				<ul class="nav pull-left">
					<li><a class="brand" href="<?= $this->https('admin') ?>"><?= $this->_('site.name') ?></a></li>
					<? foreach (Config::get('admin_nav')->left as $key => $val) { ?>
						<? if (is_object($val)) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?= $key ?> <b class="caret"></b>
							</a>
							<? foreach ($val as $key2 => $val2) { ?>
							<ul class="dropdown-menu">
								<li><a href="<?= $this->https($val2) ?>"><?= $key2 ?></a></li>
							</ul>
							<? } ?>
						</li>
						<? } ?>
					<? } ?>
				</ul>
				<ul class="nav pull-right">
					<? foreach (Config::get('admin_nav')->right as $key => $val) { ?>
						<? if (is_object($val)) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?= $key ?> <b class="caret"></b>
							</a>
							<? foreach ($val as $key2 => $val2) { ?>
							<ul class="dropdown-menu">
								<li><a href="<?= $this->https($val2) ?>"><?= $key2 ?></a></li>
							</ul>
							<? } ?>
						</li>
						<? } ?>
					<? } ?>
				</ul>
			</div>
		</div>
	</div>
</div>