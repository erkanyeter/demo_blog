$(document).ready(function() {
	// Balance Hide / Show //
		$(".header-userdetail-balance-toggle").click(function()
		{
			$(".header-userdetail-balance-wrap").animate({width:'toggle'},200)
		});
	// Balance Hide / Show //
	// Language Select add select class //
		$(".modal-language-table-right li").click(function()
		{
			$(".modal-language-table-right li").removeClass('selected')
			$(this).addClass('selected');
		});
		$(".modal-language-table li").click(function()
		{
			$(".modal-language-table li").removeClass('selected')
			$(this).addClass('selected');
		});
	// Language Select add select class //
	// Header Modal Save / Hide //
		$("#header-modal .modal-footer button").click(function()
		{
			$("#header-modal").modal('hide');
		});
	// Header Modal Save / Hide //
	// Header Login Error Modal //
		$('.header-login-form').submit(false); // Form is disabled while in debug mode.. //
		$(".login-button").click(function()
		{
			$("#login-error-modal").modal("show").stop();
		});
	// Header Login Error Modal//
});