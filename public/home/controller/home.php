<?php

/**
 * $c home
 * @var Controller
 */
$c = new Controller(function(){
    // __construct
	new Url;
	new Html;
});

$c->func('index', function() use($c){

    $c->view('home', function() use($c) {
        
        $this->set('title', 'Welcome to home');

        // $this->set('header', $c->tpl('header', false));
        // $this->set('sidebar', $c->tpl('sidebar', false));
        // $this->set('footer', $c->tpl('footer', false));
        $this->getScheme();

    });
    
});

/* End of file home.php */
/* Location: .public/home/controller/home.php */