<?php

/**
 * $c Header Controller
 *
 * @var Private View Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Auth;
        new View;
        $this->config->load('navbar');  // load navigation bar in header template.
    }
);

$c->func(
    'index',
    function () {
        $firstSegment   = $this->request->global->uri->getSegment(0);     // Get first segnment
        $currentSegment = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty
        
        $li = '';
        $auth = $this->auth->hasIdentity();

        foreach ($this->config->getItem('navigation') as $key => $value) {
            $active = ($currentSegment == $key) ? ' id="active" ' : '';

            if ( ($key == 'membership/login' OR $key == 'membership/signup') AND $auth == true) {  
                // don't show login button
            } else {
                $li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
            }
        }

        // ** Hvc View Controller output must be string;

        echo $this->view->get(
            'header',
            function () use ($li) {
                $this->set('li', $li);
            },
            false
        );
    }
);