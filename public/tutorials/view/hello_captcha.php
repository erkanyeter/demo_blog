<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo $this->html->css('welcome.css') ?>
        <title>Hello Captcha</title>

<script type="text/javascript">
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

function refreshCaptcha()
{
    refreshCaptchaUrl = '/tutorials/hello_captcha_create/'+Math.random();
    document.getElementById("captcha").src=refreshCaptchaUrl;     
    return false; // Do not do form submit;
}

</script>
    </head>
    <body>
        <header>
            <?php echo $this->url->anchor('/', $this->html->img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        <h1>Hello Captcha</h1>
        <section><?php echo $this->form->getNotice() ?></section>
        <section><?php echo $this->form->getMessage() ?></section>
        <section><?php echo $this->form->getErrorString() ?></section>

        <section>

            <?php echo $this->form->open('tutorials/hello_captcha/index', array('method' => 'POST')) ?>

                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo $this->form->label('Email') ?></td>
                        <td><?php 
                            echo $this->form->error('email');
                            echo $this->form->input('email', $this->form->setValue('email'), " id='email' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->form->label('Password') ?></td>
                        <td><?php 
                            echo $this->form->error('password');
                            echo $this->form->password('password', '', " id='password' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                        <img src="/tutorials/hello_captcha_create" alt="" id="captcha">
                        <a href="#" onclick="refreshCaptcha()" id="image">Refresh</a> 
                        </td>
                    </tr>
                    <tr>
                        <td>Captcha Code</td>
                        <td><?php 
                            echo $this->form->error('captcha');
                            echo $this->form->input('captcha', $this->form->setValue('captcha'), " id='captcha' ");
                            ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo $this->form->submit('dopost', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>

                    </table>
                
                <?php echo $this->form->close() ?>

        </section> 
        
        <section>
            <p>Total memory usage <?php echo round(memory_get_usage()/1024/1024, 2).' MB' ?></p>
        </section>
    </body>
</html>