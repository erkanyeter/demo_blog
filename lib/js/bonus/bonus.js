$(document).ready(function() {
	var show_bonus_history 	 = $("#show_bonus_history");
	var show_bonus_details 	 = $("#show_bonus_details");

	$(show_bonus_history).click(function()
	{
		$(show_bonus_details).removeClass('active-button')
		$(this).toggleClass('active-button')
		$(this).closest('div').find("div.bonus-details-container").hide();
		$(this).closest('div').find("div.bonus-history").toggle();
	});
	$(show_bonus_details).click(function()
	{
		$(show_bonus_history).removeClass('active-button')
		$(this).toggleClass('active-button')
		$(this).closest('div').find("div.bonus-history").hide();
		$(this).closest('div').find("div.bonus-details-container").toggle();
	});
	$("#show_detail").click(function()
	{
	
	var b_detail 			 = $(this).closest("tr").next("tr.b_detail");
	var b_detail_current 	 = $(this).closest("tr").next("tr.b_detail").css('display'); 

		if (b_detail_current=="none")
			{
				$(this).html("-");
			}
		else if(b_detail_current=="table-row")
			{	
				$(this).html("+")
			}
		$(b_detail).toggle()
	});

});