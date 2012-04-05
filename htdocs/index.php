<?php
require '../common/common.php';

View::getByPath($_GET['q']);

$controller = Controller::getByPath($_GET['q']);
Filter::setController($controller);
Filter::before();
$view = Controller::execByPath($_GET['q']);
Filter::after();

if ($view) {
	$view->display();
} else {
	if (!View::displayView()) {
		Error::not_found('no-template');
	}
}