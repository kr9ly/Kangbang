<frame:admin,_page.name>
<h2>_{page.admin_user}</h2>
<table: db,admin_user_table,$dao,10,admin_user_id,admin_user_name,insert_date,update_date />
<modal: table,#admin_user_table,admin/admin_user_list/edit />
</frame:admin>