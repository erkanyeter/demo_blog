<?php

/**
 * $c about
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
	new View;
});

$c->func('index', function(){

    $this->view->get('about', function() {

        $this->set('title', 'About');
        $this->getScheme();
    });
    
});

/* End of file about.php */
/* Location: .public/about/controller/about.php */