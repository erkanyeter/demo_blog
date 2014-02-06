<?php

/**
 * $c hello_odm
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    new Post;      
    new Url;
    new Html;
    new Form_Builder;
    new View;
    
    new Model('user', 'users'); 

    /**
     * FORM BUILDER VERSION IS @ALPHA
     * IT IS NOT RELEASES YET !!
     */

});

$c->func('index', function(){
    
    $this->form_builder->open('/tutorials/hello_form_builder', array('method' => 'post'), function() {

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Email',
            'rules' => 'required|validEmail',
            'input' => $this->input('user_email', $this->setValue('user_email')),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Password',
            'rules' => 'required|minLen(6)',
            'input' => $this->password('user_password', $this->setValue('user_password')),
        ) );
        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Confirm',
            'rules' => 'required|matches(user_password)',
            'input' => $this->password('confirm_password', $this->setValue('confirm_password'), ' id="confirm" ' ),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Privacy & Policy : ',
            'rules' => 'required|contains(n,y)',
            array("label" => "Yes", "input" => $this->radio("pp", 'y') ),
            array("label" => "No", "input" => $this->radio("pp", 'n') ),
        ));

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => 'Security Image',
            'rules' => 'required',
            'input' => $this->captcha('answer')
        ));

        /*
        if (is_callable($closure))
        {
            call_user_func_array(Closure::bind($closure, getInstance(), 'Controller'), array());
        }*/

        $this->addRow();
        $this->setPosition('label', 'left');
        $this->addCol(array(
            'label' => '&nbsp;',
            'input' => $this->submit('dopost', 'Do Post', ' id="dopost" '),
        ));
    });

    if($this->post->get('dopost'))
    {
        $this->form_builder->isValid();
    }

    $this->view->get('hello_form_builder', function() {

        $this->set('name', 'Obullo');
        $this->set('footer', $this->tpl('footer', false));
    });

});

/* End of file hello_odm.php */
/* Location: .public/tutorials/controller/hello_odm.php */