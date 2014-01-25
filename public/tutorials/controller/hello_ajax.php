<?php

/**
 * $c hello_ajax
 * @var Controller
 */
$c = new Controller(function(){
    // _construct
    
    new Form;
    new Request;

    new Model('user', 'users');
});


$c->func('index', function(){

    if($this->request->isXmlHttp()) // Is request Ajax ? 
    {
        new Get;

        $this->user->data['user_email']    = $this->get->post('user_email');
        $this->user->data['user_password'] = $this->get->post('user_password');

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

        echo json_encode($this->user->getOutput());
    } 
    else 
    {
        new Url;
        new Html;
        new View;

        $this->view->get('hello_ajax', function(){
                
            $this->set('name', 'Obullo');
            $this->set('title', 'Hello Ajax World !');

            $this->getScheme('welcome');
        });
    }
    
});

/* End of file hello_ajax.php */
/* Location: .public/tutorials/controller/hello_ajax.php */