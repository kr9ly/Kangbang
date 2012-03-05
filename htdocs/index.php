<?php
require '../common/common.php';

$view = Controller::execByPath($_GET['q']);
if ($view) {
	$view->display();
} else {
	if (!View::displayView()) {
	    $view = View::getByPath($_GET['q']);
        if ($view) {
            $view->display();
        } else {
            die('404 not found');
        }
	}
}