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
        $this->form->setRules('email', 'Email', 'required|validEmail|callback_test');
        $this->form->setRules('password', 'Password', 'required|minLen(6)');
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        $this->form->setRules('agreement', 'User Agreement', '_int|required');
        
        if($this->form->isValid())
        {        
            $this->form->setNotice('Form Validation Success', SUCCESS);  // Set flash notice using Session Class.
            $this->url->redirect('tutorials/hello_form/index');          // Redirect to user same page.
        }
    }

    $this->view->get('hello_form', function(){

        $this->set('name', 'Obullo');
        $this->set('footer', $this->getTpl('footer', false));

    });

});

/* End of file hello_form.php */
/* Location: .public/tutorials/controller/hello_form.php */