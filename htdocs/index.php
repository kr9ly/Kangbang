<?php
require '../common/common.php';

View::getByPath($_GET['q']);

$view = Controller::execByPath($_GET['q']);

if ($view) {
	$view->display();
} else {
	if (!View::displayView()) {
		Error::not_found('no-template');
	}
}