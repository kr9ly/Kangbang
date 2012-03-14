<?php
class SelectFormCommonParts extends Parts {
    public function exec($name,$select,$selected = '') {
        TemplateView::get()->setParam('name',$name);
        TemplateView::get()->setParam('selected',$selected);
        TemplateView::get()->setParam('select',$select);
    }
}