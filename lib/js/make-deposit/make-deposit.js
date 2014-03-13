$(document).ready(function() {
	
	// Show Description //

	$("input[type=radio]").click(function()
	{
			var name  		= $(this).closest('div').next('div').find('input[type=hidden].option-name').val()
			var value 		= $(this).closest('div').next('div').find('input[type=hidden].option-value').val()
			var data 		= $(this).closest('div').next('div').find('input[type=hidden].option-name').attr('data')
			var payment_n	= $(".payment_n");
			var payment_d 	= $(".payment_d");
			var container 	= $(".payment-description");

			$(container).show();
			$(payment_n).empty().html(name);
			$(payment_d).empty().html(value);
			$("#submit").attr('selected_option', data);
	});

	// Show Description //

var data_1 = "data_1.html"
var data_2 = "data_2.html"
var data_3 = "data_3.html"
var data_4 = "data_4.html"
var data_5 = "data_5.html"
var data_6 = "data_6.html"
var data_7 = "data_7.html"


	// Mext Step  (Step2) // 
		$("#submit").click(function()
		{
			$("form.deposit-step1").hide()
			$(".deposit-hint").hide()
			$(".payment-description").hide()
			var selected_option = $(this).attr('selected_option');
			
			/*if (selected_option=="1")
			{	
				$(".make-deposit-title").empty().html("<h2>Para Yatır - Visa / Master Kredi Kartı</h2>")
				$(".depositContainer").load(data_1)
				
			};
			*/
				$(".make-deposit-title").empty().html("<h2>Para Yatır - Visa / Master Kredi Kartı</h2>")
				$(".depositContainer").load(data_1)

		});


	// Mext Step  (Step2) //  
	
});