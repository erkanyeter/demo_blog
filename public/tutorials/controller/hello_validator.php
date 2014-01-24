<?php

/**
 * $c hello_validator
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Get;
    new Url;
    new Html;
    new View;
});

$c->func('index', function() use($c){  

    new Form;

    if($this->get->post('dopost'))
    {
        $this->form->setRules('email', 'Email', 'required|validEmail');
        $this->form->setRules('password', 'Password', 'required|minLen(6)');
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        $this->form->setRules('agreement', 'User Agreement', '_int|required');

        if($this->form->isValid())
        {        
            $this->form->setNotice('Validation Success', SUCCESS);    // Set flash notice using Session Class.
            $this->url->redirect('tutorials/hello_validator'); // Redirect to user same page.
        }
    }

    $this->view->get('hello_validator', function(){

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));

    });

});

/* End of file hello_validator.php */
/* Location: .public/tutorials/controller/hello_validator.php */