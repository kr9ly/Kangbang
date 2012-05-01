<?php
class PageAdminPage extends Page {
	public function exec() {
		TemplateView::get()->setParam('dao',PageDao::get());
	}
}