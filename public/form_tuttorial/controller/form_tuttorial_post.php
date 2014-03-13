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
        new Post;
    }
);

$c->func(
    'index',
    function () {

        if ($this->request->isXmlHttp()) { 
            new Hvc;
        	$jsonData = json_decode($this->hvc->get('form_tuttorial/form_json/register'));
        	
        	foreach($jsonData->inputs as $value){
        		

        		switch ($value->type){

        			case 'hidden' :
        			case 'subheader' : 
        				continue;
    				break;
        			case 'groupDropdown' : 
        				foreach($value->groupDropdown as $val){
        					$this->form->setRules($val->name, $val->label, $val->rules);	
        				}        				
        			break;
        			default : 

   						if($value->name == 'captcha'){
   							new Captcha;
   							$this->form->func(
				                'callback_captcha',
				                function () {
				                    $this->setMessage('callback_captcha', 'Wrong Captcha Code');
				                    $this->captcha->sendOutputHeader();
				                    return $this->captcha->check($this->post->get('captcha'));
				                }
				            );
   						}
						$this->form->setRules($value->name, $value->label, $value->rules);
        			break;

        		}

        	}

            if ( $this->form->isValid() ) {
                $this->form->setMessage('User saved successfully !');
            }

            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json;charset=UTF-8');

            echo json_encode($this->form->getOutput());

        } else {
            echo "Access denied!";
        }
    }
);

/* End of file hello_ajax.php */
/* Location: .public/tutorials/controller/hello_ajax.php */