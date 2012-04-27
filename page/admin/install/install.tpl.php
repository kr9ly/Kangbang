<frame:admin/login>
<form class="form-horizontal" method="POST">
	<fieldset>
		<legend>
			_{page.install_message}
		</legend>
		<parts:common/alert/error,$errors['message'] />
		<div class="control-group">
			<label class="control-label" for="development_mode">
			_{page.development_mode}
			</label>
			<div class="controls">
				<form: select,development_mode,$development_mode,1 />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_type">
			_{page.db_type}
			</label>
			<div class="controls">
				<form:select,db_type,$db_type />
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_server'] ? ' error' : '' ?>">
			<label class="control-label" for="db_server">
			_{page.db_server}
			</label>
			<div class="controls">
				<form: text,db_server,,_page.db_server.placeholder,$errors['db_server'] />
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_username'] ? ' error' : '' ?>">
			<label class="control-label" for="db_username">
			_{page.db_username}
			</label>
			<div class="controls">
				<form: text,db_username,,_page.db_username.placeholder,$errors['db_username'] />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_password">
			_{page.db_password}
			</label>
			<div class="controls">
				<form: password,db_password,,_page.db_password.placeholder />
			</div>
		</div>
		<div class="control-group<?= $this->errors['db_database'] ? ' error' : '' ?>">
			<label class="control-label" for="db_database">
			_{page.db_database}
			</label>
			<div class="controls">
				<form: text,db_database,'',_page.db_database.placeholder,$errors['db_database']) />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="session_type">
			_{page.session_type}
			</label>
			<div class="controls">
				<form: select,session_type,$session_type >
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="cache_type">
			_{page.cache_type}
			</label>
			<div class="controls">
				<form: select,cache_type,$cache_type />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="default_lang">
			_{page.default_lang}
			</label>
			<div class="controls">
				<form: select,default_lang,$default_lang />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="default_timezone">
			_{page.default_timezone}
			</label>
			<div class="controls">
				<form: select,default_timezone,$default_timezone />
			</div>
		</div>
		<div class="control-group<?= $this->errors['admin_user_name'] ? ' error' : '' ?>">
			<label class="control-label" for="admin_username">
			_{page.admin_username}
			</label>
			<div class="controls">
				<form: text,admin_username,,_page.admin_username.placeholder,$errors['admin_user_name'] />
			</div>
		</div>
		<div class="control-group<?= $this->errors['password'] ? ' error' : '' ?>">
			<label class="control-label" for="admin_pass">
			_{page.admin_pass}
			</label>
			<div class="controls">
				<form: text,admin_pass,,_page.admin_pass.placeholder,$errors['password'] />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="enable_https">
			_{page.enable_https}
			</label>
			<div class="controls">
				<form: checkbox,enable_https,_page.enable_https.label,1,false />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="db_initialize">
			_{page.db_initialize}
			</label>
			<div class="controls">
				<form: checkbox,db_initialize,_page.db_initialize.label,1,true />
			</div>
		</div>
		<div class="form-actions">
			<form: submit,install,_page.button.install />
		</div>
	</fieldset>
</form>
</frame:admin/login>