<html>
	<head>
		<title><?= $this->_('site.name') ?> - <?= $this->_('page.admin') ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?=$this->http('css/bootstrap.min.css') ?>" rel="stylesheet">
		<link href="<?=$this->http('css/bootstrap-responsive.min.css') ?>" rel="stylesheet">
		<script src="<?= $this->http('js/jquery-1.7.1.min.js') ?>"></script>
		<script src="<?= $this->http('js/bootstrap.min.js') ?>"></script>
		<script src="<?= $this->http('js/main.js') ?>"></script>
	</head>
	<body>
		<? Parts::display('admin/navbar') ?>
		<div class="container">
