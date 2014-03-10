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
        $this->translator->load('hello_form');
    }
);

$c->func(
    'index',
    function () {
        if ($this->request->isXmlHttp()) { // Is request Ajax ?
            
            $this->form->setRules('email', 'Email', 'required|validEmail');
            $this->form->setRules('password', 'Password', 'required|minLen(6)');
            $this->form->setRules('confirm_password', 'Confirm Password', 'required|matches(password)');
            $this->form->setRules('agreement', 'User Agreement', 'required|exactLen(1)');

            if ($this->form->isValid()) {
                // $this->form->setMessage('There are some errors.');
                $this->form->setError('email', 'Custom Error Example: There is an error in email field !');
            }

            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Content-type: application/json;charset=UTF-8');

            echo json_encode($this->form->getAllOutput());

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