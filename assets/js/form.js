$(document).ready(function(){
	
	    var formTemplate = $.get( "/assets/js/form.tpl", function(data) {
			$( "#creteFormId" ).append( data );
		})

		formTemplate.always(function() {
			formApp.createFormTemplate(jsonData);
			formApp.formValidate();
		});		

});

function formpost(formId){	
	$.post( $('#registerForm').attr( "action" ), $( '#registerForm' ).serialize(), function(data){
		
    });
}

function refreshCaptcha()
{
	refreshCaptchaUrl = '/tutorials/hello_captcha_create/'+Math.random();		    
	document.getElementById("captchaImg").src = refreshCaptchaUrl;
	return false; // Do not do form submit;
}

var formApp = new function() {

	this.myForm = "";
	this.jsonFormData = "";

	this.createFormTemplate = function (formData) {
    	var template = $(".template").html();
    	$("#creteFormId").html(_.template(template,{formData:formData}));  
    	this.myForm = document.getElementById(formData.formId);
    	this.jsonFormData = formData;
    };

    this.formValidate = function (formData) {

    	this.formValidateParserData = this.formValidateParser();
  		$("#registerForm").validate({
            rules: {
                user_firstname: "required"
            }
        });
    };

    this.formValidateParser = function () {

    	//console.log();
    	var documentId = '#' + this.myForm.getElementsByTagName('input')[0].id;
		var elementId = document.querySelector(documentId);
		//console.log(elementId.dataset.validate);
    }
}