<?php

/**
 * $c hello_validator
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Url;
    new Html;
    new View;
    new Form;
    new Post;
});

$c->func('index', function() use($c){  

    if($this->post->get('dopost'))
    {
        // $this->form->setRules('email', 'Email', 'required|validEmail|callback_test');
        // $this->form->setRules('password', 'Password', 'required|minLen(6)');
        // $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        // $this->form->setRules('agreement', 'User Agreement', '_int|required');

        $this->form->func('callback_test', function(){
            $a = 1; $b = 2;
            if($a != $b)
            {
                $this->setMessage('callback_test', 'Example callback function test message !');
                return false;
            }
        });

        if($this->form->isValid())
        {        
            $this->form->setNotice('Validation Success', SUCCESS);    // Set flash notice using Session Class.
            $this->url->redirect('tutorials/hello_validator');        // Redirect to user same page.
        }
    }

    $this->view->get('hello_form', function(){

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));

    });

});

/* End of file hello_validator.php */
/* Location: .public/tutorials/controller/hello_validator.php */