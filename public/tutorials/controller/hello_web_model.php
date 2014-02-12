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

    // $res = $this->web->query('members/create.one.json',function(){
    //     $this->data['user_username'] = 'test';
    //     $this->data['user_email']    = 'me@test.com';
    // });

    // print_r($res);
    // print_r($this->web->getResultArray());

    // var_dump($this->web->getResultArray());

    $r = $this->web->query('members/getby.id.json',function(){
        $this->data['user_id'] = '3'; // get one user
    });

    if($r['messages']['success'])
    {
        print_r($this->web->getRowArray());
    }

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