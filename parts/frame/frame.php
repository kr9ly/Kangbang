<?php
class FrameParts {
	public function exec($html,$title = '') {
		TemplateView::get()->setParam('innerHtml',$html);
		TemplateView::get()->setParam('title',$title);
	}
}