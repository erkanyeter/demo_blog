
/**
 * Obullo Framework.
 * @author rabihsyw <rabihsyw@gmail.com>
 * Form template-creating & validating plugin
 * 
 * @type Object
 */


var form = function(){
    var obj = {
		success			: true,
		formElement		: '',
		containerElement: '',
		formId 			: '',
		containerId 	: '',
		response	 	: false,
		template 		: '',
		emailRegex : /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i,
        passwordRegex: /^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(\w{8,15})$/,
    };

    obj.loadingClosure = function(){}; // closure for loading when ajax submit

    obj.createTemplate = function (containerId, jsonData) { // this function builds form template

    	obj.jsonData = jsonData;
    	// get container element //
    	obj.containerId = containerId;
    	obj.containerElement = $("#"+obj.containerId);

    	$.ajax({
    		url : "/assets/js/form.tpl",
    		success: function (data) {
    			obj.template = data;
    		},
    		async: false
    	});
    	
    	var template = $(obj.template).html();
    	obj.containerElement.html(_.template(template,{formData:obj.jsonData}));
    	
    	// get form info. Important
    	obj.setFormId(obj.jsonData.formId);

    	// obj.formId = 'frm'+ Math.floor((Math.random()*10)+1);
    	// obj.formElement.attr('id', obj.formId);
    };

    obj.construct = function(){
    	// some statements here for constructor. //
    };

    obj.construct();

    obj.setFormId = function (id) {
    	obj.formId = id;
    	obj.formElement = $('form#'+obj.formId);
    }

    obj.setSuccess = function(res){
        if(obj.success == true)
        {
            obj.success = res;
        }
    };

    var getInputObj = function (input) { // this function return jquery-object for an input-name
    	if(typeof input == 'string')
        {
            input = obj.formElement.find('*[name='+input+']');
        }
        return input;
    };

    obj.setError = function(ref, msg, input){ // setting input's error message
        
        obj.setSuccess(false);

        inputObj = getInputObj(input);

        var err = $(document.createElement('div')).attr({'class':'form-error', 'ref' : ref}).html(msg).hide();
        inputObj.parent('div').append(err.fadeIn());
    };

    obj.removeError = function(ref, input){ // delete input's error message
    	
    	inputObj = getInputObj(input);

        inputObj.parent('div').find("div[ref="+ref+"]").fadeOut().remove();
    }

    obj.validateEmail = function(input){

        var ref = 'emailError';

        inputObj = getInputObj(input);

        if( ! obj.emailRegex.test(inputObj.val()) )
        {
            obj.setError(ref,'Invalid email address', input);
        }
    };

    obj.validatePassword = function(input){
        var ref = 'passwordError';

        inputObj = getInputObj(input);

        if( ! obj.passwordRegex.test(inputObj.val()) )
        {
            obj.setError(ref,'Password must be minimum 8 characters having at least 1 digit and 1 small letter 1 capital letter.', input);
        }
    };

    obj.requiredCheck = function(input){
        var ref = 'required';

        inputObj = getInputObj(input);
        
        if(inputObj.attr('type') == 'radio' || inputObj.attr('type') == 'checkbox')
        {
            if(! inputObj.is(':checked'))
            {
                obj.setError(ref,'This field is required.', input);
            }
        }
        else if(inputObj.val() === null || inputObj.val() === '')
        {
            obj.setError(ref,'This field is required.', input);
        }
    };

    obj.checkIsInteger = function (input) {
        var ref = 'isInt';
        var intRegex = /^\d+$/;

        inputObj = getInputObj(input);

        // inputObj = obj.formElement.find('*[name='+input+']');

        if(! intRegex.test(inputObj.val())) {
           obj.setError(ref,'Must be an integer value.', input);
        }
    }

    obj.checkIsString = function (input) {
        var ref = 'isInt';
        var strRegex = /^[a-zA-Z\u00C0-\u00ff]+$/;

        // inputObj = obj.formElement.find('*[name='+input+']');
        inputObj = getInputObj(input);

        if(! strRegex.test(inputObj.val())) {
           obj.setError(ref,'Shouldn\'t contain numbers or special characters.', input);
        }
    }

    obj.checkMatch = function(second, first){
        var ref = 'matches';

        secondObj = getInputObj(second);
        firstObj = getInputObj(first);

        if(secondObj.val() != firstObj.val())
        {
            obj.setError(ref,'It doesn\'t match', second);
        }
    }

    obj.minLen = function(input, len){
        var ref = 'minlen';

        inputObj = getInputObj(input);

        if(inputObj.val().length < len)
        {
            obj.setError(ref,'Must be at least '+len+' characters.', input);
        }
    }

    obj.maxLen = function(input, len){
        var ref = 'minlen';

        inputObj = getInputObj(input);

        if(inputObj.val().length > len)
        {
            obj.setError(ref,'Must be maximum '+len+' characters.', input);
        }
    }

    obj.isValid = function(){

        obj.success = true;

        obj.formElement.find('.form-error').remove();
        $('.form-message').remove();
        
        var rulesElements = obj.formElement.find("*[data-validate]");

        rulesElements.each(function(){
        	obj.validateRules($(this).attr('name'), $(this).data('validate'));
        });

        var passwords = obj.formElement.find('input[type=password]');
        for(var i = 0; i < passwords.length ; i++)
        {
            obj.validatePassword($(passwords[i]));
        }

        return obj.success;
    };

    obj.validateRules = function(input, rules){

        var str_split = rules.split('|');
        if(str_split.length > 0)
        {
            for(var i in str_split)
            {
                if(str_split[i] == 'required')
                {
                    obj.requiredCheck(input);
                }

                if(str_split[i] == 'validEmail')
                {
                    obj.validateEmail(input);
                }

                if(/^matches/i.test(str_split[i]))
                {
                    var res = str_split[i].match( /matches\((.*?)\)/i );
                    
                    obj.checkMatch(input, res[1]);
                }

                if(/^minlen/i.test(str_split[i]))
                {
                    var res = str_split[i].match( /minlen\((.*?)\)/i );
                    
                    if(res[1].length > 0)
                    {
                        obj.minLen(input, res[1]);
                    }
                }

                if(/^maxlen/i.test(str_split[i]))
                {
                    var res = str_split[i].match( /maxlen\((.*?)\)/i );
                    
                    if(res[1].length > 0)
                    {
                        obj.maxLen(input, res[1]);
                    }
                }

                if(/^isInteger/i.test(str_split[i]))
                {
                    obj.checkIsInteger(input);
                }
            }
        }
    };

    obj.ajaxPost = function(closure){

        var xmlhttp;

        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
        }

        xmlhttp.onreadystatechange=function(){

            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                
                obj.setResponse(xmlhttp.responseText); // setting response body to form object

                if( typeof closure === 'function'){
                    closure(xmlhttp.responseText);
                }
            }
        }

        xmlhttp.open("POST",obj.formElement.attr('action'),false);
        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlhttp.send(new FormData(obj.formElement.get( 0 )));
    };

    obj.processErrorsResponse = function(errors){
        for(var i in errors){
            if(i != '')
            {
                var input = obj.formElement.find('*[name='+i+']');
                
                if(input.length == 0){
                    obj.processMessagesResponse(errors[i]);
                    console.log('setError : form-input not found');
                }else{
                   obj.setError('autoMsg', errors[i], input);
                }
            }
        }
    };

    obj.processMessagesResponse = function(messages){
        var msgWrapper = obj.formId+'-message';
        $('#'+msgWrapper).html('');
        var msgsContainer = $(document.createElement('div')).attr('id', msgWrapper).addClass('form-message');
        if($.isArray(messages)) {
            if(messages.length > 0){
                for(var i in messages)
                {
                    var msg = $(document.createElement('div')).html(messages[i]);
                    $(msgsContainer).append(msg);
                }
                obj.formElement.parent('div').before(msgsContainer);
            }
        }else if(messages.length > 0){
            var msg = $(document.createElement('div')).html(messages);
            $(msgsContainer).append(msg);
            obj.formElement.parent('div').before(msgsContainer);
        }
    };
    
    
    obj.checkAjax = function () {
    	return true;
    };

    obj.whenSubmit = function(closureAfter, closureBefore){

        obj.closureAfter = closureAfter;
        obj.closureBefore = closureBefore;

        $(document).on('submit', "#"+obj.formId, function(){

            console.log(obj.formId + ' -> submitting');

            if( obj.isValid() )
            {
                var execute = true; // beforeClosure return true or false;

                if(typeof obj.closureBefore === 'function')
                {
                    execute = obj.closureBefore();
                }

                if(obj.checkAjax())
                {
                    if(execute){

                        if(typeof obj.loadingClosure == 'function') {
                            obj.loadingClosure();
                        }
                        
                        console.log('sending xmlhttp-request ....');

                        obj.ajaxPost( function(res){

                        	if (typeof obj.closureAfter == 'function') {
	                            obj.closureAfter(res);
                        	}

                            res = JSON.parse(res);

                            if(res.hasOwnProperty('redirect')){
                                window.location.replace(res.redirect);
                            }

                            if(res.hasOwnProperty('message'))
                            {
                                obj.processMessagesResponse(res.message);
                            }

                            if(res.hasOwnProperty('errors'))
                            {
                                obj.processErrorsResponse(res.errors);
                            }
                        } );
                    }
                    return false;
                }
                return execute;
            }
            return false;
        });
    };

    return obj;
};