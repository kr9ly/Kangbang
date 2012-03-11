<html>
	<head>
		<title><?= $this->_('site.name') ?> - <?= $this->_('page.admin') ?></title>
		<link href="<?=$this->http('css/bootstrap.min.css') ?>" rel="stylesheet">
		<link href="<?=$this->http('css/bootstrap-responsive.min.css') ?>" rel="stylesheet">
	</head>
	<body>
		<? Parts::display('admin/navbar') ?>
		<div class="container">
