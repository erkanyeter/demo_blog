<?php
/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
| This file specifies the your application "services" and "service providers".
| 
| Put services here.
*/
/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/
$c['db'] = function () use ($c) {
    return $c['app']->db = new Obullo\Database\Pdo\Mysql($c['config']['database']);
};
/*
|--------------------------------------------------------------------------
| Crud ( Active Record )
|--------------------------------------------------------------------------
*/
$c['crud'] = function () use ($c) {
    return $c['app']->db = new Obullo\Crud($c['db']);  // Replace database object with crud.
};
/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/
$c['sess'] = function () use ($c) {
    return $c['app']->sess = new Obullo\Http\Session\Native($c['config']['session']);
};
/*
|--------------------------------------------------------------------------
| Cache
|--------------------------------------------------------------------------
*/
$c['cache'] = function () use ($c) {
    return $c['app']->cache = new Obullo\Cache\Redis($c['config']['cache']);
};

/*
|--------------------------------------------------------------------------
| PROVIDERS
|--------------------------------------------------------------------------
|
| Put service providers here.
*/
/*
|--------------------------------------------------------------------------
| NoSQL
|--------------------------------------------------------------------------
*/
$c['mongo'] = function ($params) use ($c) {
    $mongoClient = new MongoClient('mongodb://root:12345@localhost:27017/'.$params['db.name']);
    return new MongoCollection($mongoClient->{$params['db.name']}, $params['db.collection']);
};


/* End of file services.php */
/* Location: .services.php */