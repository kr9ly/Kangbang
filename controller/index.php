<?php
class IndexController extends Controller {
	public function exec() {
		$view = TemplateView::get();
		$view->setPath($this->getTemplate('index'));
	}
}