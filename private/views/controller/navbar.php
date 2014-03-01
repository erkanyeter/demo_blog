<?php

/**
 * $c Header Navbar Controller
 *
 * @var Private View Controller
 */
$c = new Controller(
    function () {
        new Url;
        new Auth;
        $this->config->load('navbar');  // load navigation bar in header template.
    }
);

$c->func(
    'index',
    function () {
        $firstSegment    = $this->request->global->uri->getSegment(0);     // Get first segnment
        $currentSegment  = (empty($firstSegment)) ? 'home' : $firstSegment;  // Set current segment as "home" if its empty

        $li = '';
        foreach ($this->config->getItem('navigation') as $key => $value) {
            $active = ($currentSegment == $key) ? ' id="active" ' : '';

            if ( ($key == 'membership/login' OR $key == 'membership/signup') AND $this->auth->hasIdentity() == true) {  // don't show login button

            } else {
                $li.= '<li>'.$this->url->anchor($key, $value, " $active ").'</li>';
            }
        }

        echo $li;  // View Hvc output must be string;
    }
);