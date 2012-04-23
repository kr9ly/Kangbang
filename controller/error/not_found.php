<?php
class NotFoundErrorPage extends Page {
	public function exec($reason) {
		TemplateView::get()->setParam('reason',$reason);
		header("HTTP/1.1 404 Not Found");
	}
}