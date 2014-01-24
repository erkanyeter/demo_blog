<?php

/**
 * $c hello_ajax
 * @var Controller
 */
$c = new Controller(function(){
    // _construct
    
    new Html;
    new Url;
    new Form;
    new Get;
    new View;
});


$c->func('index', function(){

    if($this->get->post('dopost'))
    {
        new Model('user', 'users');

        $this->user->data['email']    = $this->get->post('email');
        $this->user->data['password'] = $this->get->post('password');

        //--------------------- set non schema rules
        
        $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
        $this->form->setRules('agreement', 'User Agreement', '_int|required|exactLen(1)');
        
        //---------------------

        $this->user->func('save', function() { 
            if($this->isValid())
            {
                $this->data['password'] = md5($this->getValue('password'));
                return $this->db->insert('users', $this);
            }
            return false;
        });

        $this->user->save();

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json;charset=UTF-8');

        echo json_encode($this->user->output());
    } 
    else 
    {
        $this->view->get('hello_ajax');
    }
    
});

/* End of file hello_ajax.php */
/* Location: .public/tutorials/controller/hello_ajax.php */