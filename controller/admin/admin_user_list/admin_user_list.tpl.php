<? Parts::display('admin/header'); ?>
<h2><?= $this->_('page.admin_user') ?></h2>
<? Parts::display('common/table/db','admin_user_table',AdminUserDao::get(),10,admin_user_id,admin_user_name,insert_date,update_date) ?>
<? Parts::display('common/modal/table','#admin_user_table','admin/admin_user_list/edit') ?>
<? Parts::display('admin/footer'); ?>