<?php
require '../common/common.php';

$view = Controller::execByPath($_GET['q']);
if ($view) {
	$view->display();
} else {
	View::displayView();
}