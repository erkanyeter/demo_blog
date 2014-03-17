<?php

/*
|--------------------------------------------------------------------------
| Hvc Package Configuration
|--------------------------------------------------------------------------
| This file contains configuration for Hvc package.
|
| @ What is Hvc ? ( Hierarchical-View–Controller ) ( No model )
| Hvc requests compatible with Ajax Requests and also has a 
| caching functionality.
| 
| @ see the docs - The "Hvc Design Pattern" thoroughly used in Framework.
|
| Each Hvc uri creates a random connection string. And each 
| random Uri able to do Memory Cache if you provide expiration time as third paramater.
| 
*/
$hvc['memory_caching'] = true;

// Each Hvc request uri creates a random connection string (hvc key) as the following steps.
// 
// 1 - The request method gets the uri and serialized string of your data parameters
// 2 - then it builds md5 hash
// 3 - finally add it to the end of your hvc uri.
// 4 - in this technique the hvc key can be used as a "key" for caching systems.

// Example Cache Usage
// $this->hvc->get('private/comments/getuser', array('user_id' => 5), $expiration = 7200);

// @see http://demo_blog/tutorials/hello_hvc

/*
|--------------------------------------------------------------------------
| Dependeny Injection Objects
|--------------------------------------------------------------------------
| Configure dependecies
|
*/
$hvc['cache'] = function(){ 
    return new Cache;
};


/* End of file hvc.php */
/* Location: .app/config/hvc.php */