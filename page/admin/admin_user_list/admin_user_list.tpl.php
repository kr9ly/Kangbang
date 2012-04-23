<h2><?= $this->_('page.admin_user') ?></h2>
<? Parts::table('db','admin_user_table',AdminUserDao::get(),10,admin_user_id,admin_user_name,insert_date,update_date) ?>
<? Parts::modal('table','#admin_user_table','admin/admin_user_list/edit') ?>
<? Parts::frame('admin'); ?>