
/**
 * Framework
 * Ajax Object with Native Javascript
 * 
 * @type Object
 */

var process = false;
var ajax = {
    post : function(url, closure, params){
        var xmlhttp;
        if (window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest(); // code for IE7+, Firefox, Chrome, Opera, Safari
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); // code for IE6, IE5
        }
        xmlhttp.onreadystatechange=function(){
        /**
         * onreadystatechange will fire five times as 
         * your specified page is requested.
         * 
         *  0: uninitialized
         *  1: loading
         *  2: loaded
         *  3: interactive
         *  4: complete
         */
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                if( typeof closure === 'function'){
                    closure(xmlhttp.responseText);
                }
            }
        }
        xmlhttp.open("POST",url,true);
        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlhttp.send(params);
    },
    get : function() {
        // paste here
    }
}
function parseNode(obj, element, i){
    var name = element.name;
    var inputError = document.getElementById(i+'_inputError');

    //-------------------------------------------------------
    // No Response
    if(typeof obj.messages == 'undefined'){
        console.log('Data connection lost, no response data.');
        return false;
    }
    //-------------------------------------------------------
    // Success
    if(typeof obj.messages['success'] !== 'undefined' && obj.messages['success'] == '1'){
        if(typeof obj.messages['redirect'] !== 'undefined' && process == false){
            window.location.replace(obj.messages['redirect']);
            process = true; // Set process done.
        }
        if(typeof obj.messages['alert'] !== 'undefined' && process == false){
            alert(obj.messages['alert']);
            process = true; // Set process done.
            return false;
        }
    }
    //-------------------------------------------------------
    // Errors
    if(typeof obj.messages['success'] !== 'undefined' && obj.messages['success'] == '0'){
        if(typeof obj.errors[name] !== 'undefined'){
            if(inputError){
                document.getElementById(i+'_inputError').innerHTML = obj.errors[name];
            } else{
                e.innerHTML = obj.errors[name];
                element.parentNode.appendChild(e);
            }
        } else {
            if(inputError){
                document.getElementById(i+'_inputError').remove(); 
            }
        }
    }
}
function submitAjax(formId){
    var myform = document.getElementById(formId);
    myform.onsubmit = function(){
        var elements = new Array();
        elements[0] = myform.getElementsByTagName('input');
        elements[1] = myform.getElementsByTagName('select');
        elements[2] = myform.getElementsByTagName('textarea');

        var elementsClass = document.getElementsByClassName('_inputError');
        
        //--------------- AJAX ----------------//

        ajax.post( myform.getAttribute('action'), function(json){
            var obj = JSON.parse(json);
            for (var i = 0; i < elements.length; i++){
                var elemets2 = new Array();
                    elemets2 = elements[i];
                for(var ii = 0; ii < elemets2.length; ii++){
                    e = document.createElement('div');
                    e.className = '_inputError';
                    errorInputNameId = i.toString() + ii.toString();
                    e.id = errorInputNameId + '_inputError';
                    if (elemets2[ii].type != 'submit'){
                        if ( elemets2[ii].type != 'hidden') 
                        {
                            parseNode(obj, elemets2[ii], errorInputNameId);
                        }
                    }
                }
            }
        },
        new FormData(myform)
        );
        return false; // Do not do form submit;
    }
}