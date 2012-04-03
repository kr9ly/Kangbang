(function(){
	window.krkr = {
		toPostArray : function(obj) {
			var res = {};
			$(obj).find("input:hidden,input:text").each(function(){
				res[$(this).attr("name")] = $(this).val();
			});
			return res;
		}
	}
})();