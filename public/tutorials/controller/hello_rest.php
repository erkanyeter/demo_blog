<?php

/**
 * $c hello_rest
 * @var Controller
 */
$c = new Controller(function(){
    // __construct

    new Rest;
});

$c->func('index', function(){

    $this->rest->data['user_username']      = '';
    $this->rest->data['user_email']         = 'eguvenc@gmail.com';
    $this->rest->data['user_creation_date'] = date('Y-m-d H:i:s');

    if($this->rest->post('create.user'))
    {
        // success
    }
    else 
    {
        print_r($this->rest->getOutput());
    }

});

/* End of file home.php */
/* Location: .public/home/controller/home.php */