<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?= $this->https('admin') ?>"><?= $this->_('site.name') ?></a>
			<div class="nav-collapse">
				<ul class="nav pull-left">
					<? foreach (Config::get('admin_nav')->left as $key => $val) { ?>
						<? if (is_object($val)) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?= $key ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
							<? foreach ($val as $key2 => $val2) { ?>
								<li><a href="<?= $this->https($val2) ?>"><?= $key2 ?></a></li>
							<? } ?>
							</ul>
						</li>
						<? } else { ?>
						<li><a href="<?= $this->https($val) ?>"><?= $key ?></a></li>
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
							<ul class="dropdown-menu">
							<? foreach ($val as $key2 => $val2) { ?>
								<li><a href="<?= $this->https($val2) ?>"><?= $key2 ?></a></li>
							<? } ?>
							</ul>
						</li>
						<? } else { ?>
						<li><a href="<?= $this->https($val) ?>"><?= $key ?></a></li>
						<? } ?>
					<? } ?>
				</ul>
			</div>
		</div>
	</div>
</div>