<?php

/**
 * $c test
 * @var Controller
 */

$c = new Controller(
    function () {
        // __construct
        new View;
        new Url;
        new Html;
        new Captcha;
        new Form;
        new Post;
    }
);

$c->func(
    'index',
    function () {
        if ($this->post->get('dopost')) {

            $this->form->setRules('email', 'Email', 'required|validEmail|callback_test');
            $this->form->setRules('password', 'Password', 'required|minLen(6)');
            $this->form->setRules('captcha', 'Captcha', 'required|callback_captcha');

            $this->form->func(
                'callback_captcha',
                function () {
                    $this->setMessage('callback_captcha', 'Wrong Captcha Code');
                    $this->captcha->sendOutputHeader();
                    return $this->captcha->check($this->post->get('captcha'));
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