<?php
require '../common/common.php';

$controller = Controller::getByPath($_GET['q']);
if ($controller) {
	echo 'foo!';
} else {
	echo 'bar!';
}