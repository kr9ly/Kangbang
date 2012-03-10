<?php
class LoginAdminController extends Controller {
	public function exec() {
		if (AdminUserHelper::isAdmin()) {
			NavigateHelper::redirect('admin');
		}
		if ($this->isPost()) {
			$res = AdminUserHelper::login($_POST['login_id'], $_POST['password']);
			if ($res) {
				NavigateHelper::redirect('admin');
			}
			TemplateView::get()->setParam('error',$this->_('error.wrong_challenge'));
		}
	}
}