<?php
class FrameParts {
	public function exec($html) {
		TemplateView::get()->setParam('innerHtml',$html);
	}
}