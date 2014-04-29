<?php

/**
 * $o hello_ajax
 * 
 * @var Controller
 */
$o = new Controller(
    function () {
        $c['Form'];
        $c['Request'];
        
        $this->translator->load('hello_form');
    }
);

$o->func(
    'index',
    function () {
        if ($this->request->isXmlHttp()) { // Is request Ajax ?
            
            $this->form->setRules('email', 'Email', 'required|validEmail');
            $this->form->setRules('password', 'Password', 'required|minLen(6)');
            $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');

            if ($this->form->isValid()) {
                $this->form->setError('email', 'Custom Error Example: There is an error in email field !');
                $this->form->setMessage('There are some errors.');

                // $this->form->setMessage('Succesfull !');
            }

            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json;charset=UTF-8');

            echo json_encode($this->form->getAllOutput());

        } else {

            $c['Url'];
            $c['Html'];
            $c['View'];

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