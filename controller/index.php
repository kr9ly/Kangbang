<?php
class IndexController extends Controller {
	public function exec() {
		TextView::get()->set(TextHelper::toCamelCase('hello_world!!'));
	}
}