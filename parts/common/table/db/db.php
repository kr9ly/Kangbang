<?php
class DbTableCommonParts extends Parts {
	public function exec($dao,$limit = 10) {
		if (func_num_args() > 2) {
			$args = func_get_args();
			array_shift($args);
			array_shift($args);
		} else {
			$args = array_keys($dao->columns);
		}
		$cnt = $dao->count();
		$page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
		$dao->limit($limit)->offset((($page - 1) * $cnt));
		$data = call_user_func_array(array($dao,'select'), $args);
		TemplateView::get()->setParam('columns',$args);
		TemplateView::get()->setParam('data',$data);
		TemplateView::get()->setParam('page',$page);
		TemplateView::get()->setParam('maxPage',(int)($cnt / $limit) + 1);
		TemplateView::get()->setParam('dao',$dao);
	}
}