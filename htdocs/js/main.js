(function(){
	window.krkr = {
		toPostArray : function(obj) {
			var res = {};
			$(obj).find("input:hidden,input:text").each(function(){
				res[$(this).attr("name")] = $(this).val();
			});
			return res;
		}
		,convertToModal : function(obj) {
			obj.find("button,input:submit").click(function(){
				var form = $(this).closest("form");
				$.post(form.attr("action"),form.serializeArray(),function(data){
					var inner = $(data);
					krkr.convertToModal(inner);
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
		,getModalHandler : function(url) {
			return function() {
				var div = $("<div class=\"modal\"></div>");
				$("body").append(div);
				div.modal('show');
				div.on('hidden',function(){$(this).remove()});

				$.get(url,krkr.toPostArray($(this)),function(data){
					var inner = $(data);
					krkr.convertToModal(inner);
					div.html(inner);
				},"text");
			}
		}
	}
})();