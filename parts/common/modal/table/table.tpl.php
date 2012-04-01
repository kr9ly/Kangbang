<script>
$("<?= $this->selector ?>").find("tr").click(function(){
	var div = $("<div class=\"modal\"></div>");
	div.append($("<div class=\"modal-header\"><h3>title</h3></div><div class=\"modal-body\"><p>body</p></div><div class=\"modal-footer\"><a href=\"\" class=\"btn\">Close</a></div>"));
	$("body").append(div);
	div.modal('show');
	//div.on('hidden',function(){$(this).remove()});
	//$.get("<?= UrlHelper::http($this->_action) ?>",$(this).serializeArray(),function(data){
	//	div.html(data);
	//},"text");
});
</script>