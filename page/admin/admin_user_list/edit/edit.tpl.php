<form method="POST" action="<?= UrlHelper::http() ?>">
	<div class="modal-header">
		<h3>_{page.title}</h3>
	</div>
	<div class="modal-body">
		<div class="form-horizontal">
			<fieldset>
				<form: hidden,admin_user_id />
				<div class="control-group">
					<form: label,admin_user_name,_page.admin_user_name />
					<div class="controls">
						<form: text,admin_user_name,$admin_user_name />
					</div>
				</div>
				<div class="control-group">
					<form: label,password,_page.password />
					<div class="controls">
						<form: password,password />
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn modal-close">_{page.button.cancel}</a>
		<button class="btn btn-primary" >_{page.button.submit}</button>
	</div>
</form>