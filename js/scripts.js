$(document).ready(function() {
	$("#username").blur(function(event) {
		if ($(this).val()=="")
			{
				$("#null_username").show()
				$(this).addClass('warning')
				$("#username_text").addClass('warning_text')
			}
	});
	$("#password").blur(function(event) {
		if ($(this).val()=="")
			{
				$("#null_password").show()
				$(this).addClass('warning')
				$("#password_text").addClass('warning_text')
			}
	});
	$("#email").blur(function(event) {
		if ($(this).val()=="")
			{
				$("#null_email").show()
				$(this).addClass('warning')
				$("#email_text").addClass('warning_text')
			}
	});
	$("#name").blur(function(event) {
		if ($(this).val()=="")
			{
				$("#null_name").show()
				$(this).addClass('warning')
				$("#name_text").addClass('warning_text')
			}
	});
	$("#website").blur(function(event) {
		if ($(this).val()=="")
			{
				$("#null_website").show()
				$(this).addClass('warning')
				$("#website_text").addClass('warning_text')
			}
	});
	$("#comment").blur(function(event) {
		if ($(this).val()=="")
			{
				$("#null_comment").show()
				$(this).addClass('warning')
				$("#comment_text").addClass('warning_text')
			}
	});
});