<?php

/**
 * $c hello_ajax
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Form;
        new Request;
    }
);

$c->func(
    'index',
    function () {
        if ($this->request->isXmlHttp()) { // Is request Ajax ?
            new Hvc;
        	$jsonData = json_decode($this->hvc->get('form_tuttorial/form_json/register'));
        	
        	foreach($jsonData->inputs as $value){
        		
        		//$this->form->setRules($value->key, $value->name);

        	}

        	$this->form->setRules('username', 'User Agreement', 'required');

            if ( $this->form->isValid() ) {
                $this->form->setMessage('User saved successfully !');
            }

            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json;charset=UTF-8');

            echo json_encode($this->form->getOutput());

        } else {
            new Url;
            new Html;
            new View;

            $this->view->get(
                'hello_ajax',
                function () {
                    $this->set('name', 'Obullo');
                    $this->set('title', 'Hello Ajax World !');
                    $this->getScheme('welcome');
                }
            );
        }
    }
);

/* End of file hello_ajax.php */
/* Location: .public/tutorials/controller/hello_ajax.php */