<?php

/**
 * $c contact
 * 
 * @var Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Hvc;
        new View;
    }
);

$c->func(
    'index',
    function () {

        $jsonData = $this->hvc->get('form_tuttorial/form_json/register');

        $this->view->get(
            'form_tuttorial',
            function () use($jsonData) {
                $this->set('title', 'Form Tuttorial');
                $this->set('jsonData', $jsonData);
                $this->getScheme();
            }
        );

    }
);
