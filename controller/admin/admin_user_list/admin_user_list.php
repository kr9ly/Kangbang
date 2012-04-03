<?php
class AdminUserListAdminController extends Controller {
	public function exec() {

	}

	public function edit($complete = false) {
		if ($complete) {
			return;
		}
		if ($this->isPost()) {
			$params = array();
			$params['admin_user_name'] = $_POST['admin_user_name'];
			if ($_POST['password']) $params['password'] = $_POST['password'];

			AdminUserDao::get()->equalToAdminUserId($_POST['admin_user_id'])->update($params);

			NavigateHelper::redirect('admin/admin_user_list/edit/complete');
		}

		$rec = AdminUserDao::get()->selectByKey($_REQUEST['admin_user_id']);
		if ($rec) {
			$user = $rec[0];
			TemplateView::get()->setParam('admin_user_name',$user['admin_user_name']);
		}
	}
}