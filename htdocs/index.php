<?php
require '../common/common.php';

try {
	View::getByPath($_GET['q']);

	$page = Page::getByPath($_GET['q']);
	Filter::setPage($page);
	Filter::before();
	$view = Page::execByPath($_GET['q']);
	Filter::after();

	if ($view) {
		$view->display();
	} else {
		if (!View::displayView()) {
			Error::not_found('no-template');
		}
	}
} catch (Exception $e) {
	Error::internal_server($e->__toString(),$e->getTraceAsString());
}