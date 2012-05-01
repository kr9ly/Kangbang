<script>
$("<?= $this->selector ?>").find("tr").click(krkr.getModalHandler("<?= UrlHelper::http($this->_action) ?>"));
</script>