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
                    return $this->captcha->check($this->post->get('captcha'));
                }
            );

            if ($this->form->isValid()) {
                $this->form->setNotice('Form Validation Success', SUCCESS);  // Set flash notice using Session Class.
                $this->url->redirect('tutorials/hello_captcha');          // Redirect to user same page.
            }
        }
  
        $this->captcha->setDriver('secure');  // or set to "cool" with no background
        $this->captcha->setPool('alpha');
        $this->captcha->setChar(5);
        $this->captcha->setFontSize(15);
        $this->captcha->setHeight(25);
        $this->captcha->setWave(false);
        $this->captcha->setColor(array('red','black','blue'));
        $this->captcha->setNoiseColor(array('red','black','blue'));
        $this->captcha->setFont('NightSkK');
        $this->captcha->create();
        $new_image = $this->captcha->getImageUrl();
     
        $this->view->get(
            'hello_captcha',
            function () use ($new_image) {
                $this->set('name', 'Obullo');
                $this->set('title', 'Hello Captcha !');
                $this->set('newCaptcha', $new_image);
                $this->getScheme('welcome');
            }
        );
    }
);


/* End of file welcome.php */
/* Location: .public/welcome/controller/welcome.php */