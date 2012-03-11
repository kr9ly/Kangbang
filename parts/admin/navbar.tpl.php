<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<div class="nav-collapse">
				<ul class="nav pull-left">
					<li><a class="brand" href="<?= $this->https('admin') ?>"><?= $this->_('site.name') ?></a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?= $this->_('menu.user_admin') ?> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?= $this->https('admin/user') ?>"><?= $this->_('menu.user_list') ?></a></li>
						</ul>
					</li>
				</ul>
				<ul class="nav pull-right">
					<li>
						<a href="<?= $this->https('admin/logout') ?>"><?= $this->_('menu.logout') ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>