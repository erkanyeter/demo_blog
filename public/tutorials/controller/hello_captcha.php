<?php

/**
 * $o hell_captcha
 * 
 * @var Controller
 */
$o = new Controller(
    function () {
        new View;
        new Url;
        new Html;
        new Form;
        new Post;
    }
);

$o->func(
    'index',
    function () {
        if ($this->post->get('dopost')) {

            $this->form->setRules('email', 'Email', 'required|validEmail|callback_test');
            $this->form->setRules('password', 'Password', 'required|minLen(6)');
            $this->form->setRules('captcha', 'Captcha', 'required|callback_verify_captcha');

            //-------------- set your callback ---------------//

            $this->form->func(
                'callback_verify_captcha',
                function () {
                    $oaptcha = new Captcha;
                    $answer = $this->post->get('captcha_answer');
                    if ($oaptcha->check($answer) == false) {
                        $this->setMessage('callback_verify_captcha', 'Wrong Captcha Code');
                        return false;
                    }
                    return true;
                }
            );
            if ($this->form->isValid()) {
                $this->form->setNotice('Form Validation Success', SUCCESS);  // Set flash notice using Session Class.
                $this->url->redirect('tutorials/hello_captcha');          // Redirect to user same page.
            }
        }
     
        $this->view->get(
            'hello_captcha',
            function () {
                $this->set('name', 'Obullo');
                $this->set('title', 'Hello Captcha !');
                $this->getScheme('welcome');
            }
        );
    }
);


/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */