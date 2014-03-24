<?php 

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|     example.com/directory/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| Docs / Advanced / URI Routing 
|
*/
$config = array(

    'tag/(:any)'                   => 'tag/$1',
    'post/detail/(:num)'           => 'post/detail/$1',
    'post/preview/(:num)'          => 'post/preview/$1',
    'post/update/(:num)'           => 'post/update/$1',
    'post/delete/(:num)'           => 'post/delete/$1',
    'comment/delete/(:num)'        => 'comment/delete/$1',
    'comment/update/(:num)/(:any)' => 'comment/update/$1/$2',
    
    // Default Controller 
    'default_controller' => 'welcome/index', // This is the default controller, application call it as default
    
    // 404 Override
    '404_override' => '',                    // You can redirect 404 errors to specify controller

     // Controller Default Index Method
    'index_method' => 'index'                // This is controller default index method for all controllers.
                                             // You should configure it before the first run of your application.
);

/* End of file routes.php */
/* Location: .app/config/debug/routes.php */