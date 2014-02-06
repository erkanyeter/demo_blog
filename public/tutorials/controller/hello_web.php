<?php

/**
 * $c hello_web "web service"
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
    
    new Web_Model('users', 'post');
});

$c->func('index', function(){

    // $this->oql->query('SELECT * FROM orders wincache_refresh_if_changed()');
    // $this->oql->query('INSERT INTO users;');

    // $this->web->query('post','members/createOne.json',function(){
    //     $this->data['user_username'] = 'test';
    //     $this->data['user_email']    = 'test.com';
    // });



    // $this->users->data['user_username'] = 1;
    // $this->users->data['user_username'] = 1;
    // $this->users->data['user_username'] = 1;
    // $this->users->data['user_username'] = 1;
    $this->users->create_one();

    // $this->web->isValid();

    var_dump($this->users->getResultArray());

    echo '<br>';
    echo '<br>';
    echo $this->uri->getExtension().'<br/>'; 
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

/* End of file hello_web.php */
/* Location: .tutorials/controller/hello_web.php */