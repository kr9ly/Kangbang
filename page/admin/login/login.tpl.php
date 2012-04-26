<frame: admin/login,_page.name>
<form class="form-horizontal" method="POST">
	<fieldset>
		<legend>
			_{page.login_message,site.name}
		</legend>
		<alert: error,$error />
		<div class="control-group">
			<label class="control-label" for="login_id">
			_{page.login_id}
			</label>
			<div class="controls">
				<form: text,login_id,,_page.login_id />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="password">
			_{page.password}
			</label>
			<div class="controls">
				<form: password,password,,_page.password />
			</div>
		</div>
		<div class="form-actions">
			<form: submit,login,_page.button.login />
		</div>
	</fieldset>
</form>
</frame: admin/login>