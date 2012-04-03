<script>
$("<?= $this->selector ?>").find("tr").click(function(){
	var div = $("<div class=\"modal\"></div>");
	$("body").append(div);
	div.modal('show');
	div.on('hidden',function(){$(this).remove()});

	var convert = function(obj) {
		obj.find("button,input:submit").click(function(){
			var form = $(this).closest("form");
			$.post(form.attr("action"),form.serializeArray(),function(data){
				var inner = $(data);
				convert(inner);
				div.html(inner);
			},"text");
			return false;
		});
		obj.find(".modal-close").click(function(){
			var modal = $(this).closest(".modal");
			modal.modal("hide");
		});
		obj.find(".modal-reload").click(function(){
			location.reload();
		});
	}
	$.get("<?= UrlHelper::http($this->_action) ?>",krkr.toPostArray($(this)),function(data){
		var inner = $(data);
		convert(inner);
		div.html(inner);
	},"text");
});
</script>