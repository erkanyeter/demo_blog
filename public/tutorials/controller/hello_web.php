<?php

/**
 * $c hello_web "web service"
 * 
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    new Web;
});

$c->func('index', function(){

    echo $this->uri->getExtension();
    echo '<br>';

    echo $this->web->query('post','members.create_one.xml',function(){

        $this->data['user_username'] = 'eguvenc@gmail.com';

    });

    echo $this->uri->getExtension(); 
    echo '<br>';

    /*
    $this->rest->data['user_username']      = '';
    $this->rest->data['user_email']         = 'eguvenc@gmail.com';
    $this->rest->data['user_creation_date'] = date('Y-m-d H:i:s');

    if($this->rest->query('web_service/test.post'))
    {
        // success
    }
    else 
    {
        print_r($this->rest->getOutput());
    }
    */
   
    // echo $this->router->fetchDirectory();

});

/* End of file hello_rest.php */
/* Location: .web_service/members/controller/hello_rest.php */