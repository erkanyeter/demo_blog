<?php

/**
 * $c about
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
});

$c->func('index', function() use($c){

    $c->view('about', function() use($c) {

        $this->set('title', 'About');
        $this->getScheme();
        
    });
    
});

/* End of file about.php */
/* Location: .public/about/controller/about.php */