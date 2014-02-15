<?php

/**
 * $c hello_web_model "local web service"
 * 
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
});

$c->func('index', function(){

    new Web;

    $res = $this->web->query('members/create.new.json',function(){
        $this->data['user_username'] = 'test';
        $this->data['user_email']    = 'ersin@test.com';
        $this->data['user_password'] = '123456';
    });

    print_r($res);


    // $r = $this->web->query('members/getby.id.json',function(){
    //     $this->data['user_id'] = ''; // get one user
    // });

    // if($r['success'] == 1)
    // {

    // } else {

    //     echo $r['message'];
    // }

    // print_r($r);

    echo '<br>';
    echo '<br>aksldhaksjdkasjd';

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

/* End of file hello_web_model.php */
/* Location: .tutorials/controller/hello_web_model.php */