$(document).ready(function() {
 	 $(".list li.parent").click(function()
 	 {	
 	 			var id = $(this).attr('item');
				var sub = ".sub"+id;
				 	$(this).find("span:first-child").toggleClass('glyphicon-arrow-right glyphicon-arrow-down')
				 	$(sub).toggle()
 	 });
 	 $(".sub").click(function()
 	 {
 	 			var sub_id = $(this).attr('item')
 	 			var sub_x  = ".sub"+sub_id+"-sub";
 	 			var test = $(sub_x).html();
 	 			if (test)
 	 			{
				 	$(this).find("span:first-child").toggleClass('glyphicon-arrow-right glyphicon-arrow-down')
	 	 			$(sub_x).toggle()
 	 			}
 	 			else
 	 			{

 	 			};	
 	 });
	$("#refresh_tables").click(function()
		{
			$("#loading_text").remove();
			$(this).before('<div id="loading_text" style="display:inline-block;">İçerik Yeniliyor..</div>');
				setTimeout(function()
				{
					$("#loading_text").remove()
				},2000)
		});
	$("#selector").click(function()
	{
		alert("a");
	});
});	