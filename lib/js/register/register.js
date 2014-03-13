$(document).ready(function() {

        // validation inputs //
    $("input.validation").focusout(function() 
    {
        var val = $(this).val();
        if (val== '')
            {
                                $(this).next('span').remove();
                    $(this).after('<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>')
            } 
            else
            {
                                $(this).next('span').remove();
                    $(this).after('<span class="glyphicon glyphicon-ok form-control-feedback"></span>')
            };
    });
        // validation inputs //

        // validation selectboxes //
        $("select.validation").focusout(function()
        {
                var selectedvalue = $(this).val();

                if (selectedvalue == "")
                {
                     $(this).closest('div').next(".testdiv").remove()
                     $(this).closest('div').after('<div class="testdiv col-sm-1"><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span></div>')
                }
                else
                {  
                     $(this).closest('div').next(".testdiv").remove()
                     $(this).closest('div').after('<div class="testdiv col-sm-1"><span class="glyphicon glyphicon-ok form-control-feedback"></span></div>')
                };
        });
        // validation selectboxes //

        // validation phone inputs //
        $("input.input-phone-first").keyup(function()
        {
                        var length = $(this).val().length;
                        if (length==3)
                        {       
                                $(this).next('input').focus()       
                        }
        });
        $(".validation-phone-first1,.validation-phone-last1").focusout(function()
        {
                var first_input1 = $(".validation-phone-first1").val();
                var last_input1  = $(".validation-phone-last1").val();
                if (first_input1 !== ''  && last_input1 !== '')
                 {
                     $(this).closest('div').next(".glyphicon-div").remove()
                     $(this).closest('div').after('<div class="glyphicon-div col-sm-1"><span class="glyphicon glyphicon-ok form-control-feedback"></span></div>')

                }
                else
                {  
                     $(this).closest('div').next(".glyphicon-div").remove()
                     $(this).closest('div').after('<div class="glyphicon-div col-sm-1"><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span></div>')
                };
        });
        $(".validation-phone-first2,.validation-phone-last2").focusout(function()
        {
                var first_input2 = $(".validation-phone-first2").val();
                var last_input2  = $(".validation-phone-last2").val();
                if (first_input2 !== ''  && last_input2 !== '')
                 {
                     $(this).closest('div').next(".glyphicon-div").remove()
                     $(this).closest('div').after('<div class="glyphicon-div col-sm-1"><span class="glyphicon glyphicon-ok form-control-feedback"></span></div>')

                }
                else
                {  
                     $(this).closest('div').next(".glyphicon-div").remove()
                     $(this).closest('div').after('<div class="glyphicon-div col-sm-1"><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span></div>')
                };
        });
        //validation phone inputs //
        
        // validation birthday selextbox //

        $(".validation-birthday-d, .validation-birthday-m ,.validation-birthday-y").focusout(function()
        {
                var selectedvalue_d = $(".validation-birthday-d").val();
                var selectedvalue_m = $(".validation-birthday-m").val();
                var selectedvalue_y = $(".validation-birthday-y").val();

                if (selectedvalue_d !== "" && selectedvalue_m !== "" && selectedvalue_y !== "")
                {

                     $(this).closest('div').next(".glyphicon-div").remove()
                     $(this).closest('div').after('<div class="glyphicon-div col-sm-1"><span class="glyphicon glyphicon-ok form-control-feedback"></span></div>')

                }
                else
                {  
                     $(this).closest('div').next(".glyphicon-div").remove()
                     $(this).closest('div').after('<div class="glyphicon-div col-sm-1"><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span></div>')
                };
        });

        // validation birthday selextbox //

        $("div.row").tooltip();
}); 