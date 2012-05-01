<?php
class InternalServerErrorPage extends Page {
	public function exec($reason,$trace) {
		TemplateView::get()->setParam('reason',$reason);
		TemplateView::get()->setParam('trace',$trace);
		header("HTTP/1.1 500 Internal Server Error");
	}
}