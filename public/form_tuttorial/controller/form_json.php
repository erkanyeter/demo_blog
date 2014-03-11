<?php

/**
 * $c contact
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        
    }
);

$c->func(
    'index',
    function ($form) {

        switch ($form){
            case 'register': 
                $jsonData = $this->_register(); 
            break;
            default: 
                echo false;
            break;
        }

        echo $jsonData;
    }
);

$c->func(
    '_register',
    function () {

        $register = array(
            'postUrl' => 'form_tuttorial/form_tuttorial_post',
            'inputs' => array(
                array(
                    'key' => 'username',
                    'type' => 'textbox',
                    'rules' => 'required|min[6]|max[15]',
                    'name' => 'Username',
                    'dataDropdown' => array(array('key' => '2', 'name' => 'Türkiye'))
                ),
                array(
                    'key' => 'password',
                    'type' => 'password',
                    'rules' => 'required|min[8]|max[15]',
                    'name' => 'Password',
                    'dataDropdown' => array(array('key' => '3', 'name' => 'Türkiye'))
                ),
                array(
                    'key' => 'country',
                    'type' => 'dropdown',
                    'rules' => 'required|integer',
                    'name' => 'Countries',
                    'dataDropdown' => array(array('key' => '1', 'name' => 'Türkiye'),array('key' => '10', 'name' => 'Almanya'))
                ),
            )
        );

        $verify = false;

        if($verify){
            $register['inputs'][] = array(
                                            'key' => 'verify',
                                            'type' => 'textbox',
                                            'rules' => 'required|min[6]|max[6]',
                                            'name' => 'Verify',
                                            'data' => ''
                                        );
        }

        return json_encode($register);
    }
);



