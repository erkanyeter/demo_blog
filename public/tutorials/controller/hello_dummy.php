<?php

/**
 * $c hello_dummy 
 * 
 * Dummy test class for Hvc
 * 
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['Uri'];
    }
);

$app->func(
    'index', 
    function ($arg1, $arg2, $arg3) { 

        echo '<pre>Request: <span class="string">'.$this->uri->getUriString().'</span></pre>';
        echo '<pre>Response: <span class="string">'.$arg1 .' - '.$arg2. ' - '.$arg3.'</span></pre>';
        echo '<pre>Global Request: <span class="string">'.$this->request->global->uri->getUriString().'</span></pre>';
        echo '<p></p>';
    }
);

/* End of file hello_dummy.php */
/* Location: .public/tutorials/controller/hello_dummy.php */