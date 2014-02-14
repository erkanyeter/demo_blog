<?php

/*
|--------------------------------------------------------------------------
| Log Writer Mongo Package Configuration
|--------------------------------------------------------------------------
| Configure Log Writer Mongo library options
|
*/
$log_writer_mongo = array(
    
    'host'     => 'localhost',
    'port'     => '27017',
    'database' => 'test',
    'username' => 'root',
    'password' => '123456',
    'dsn'      => '', // or dsn mongodb://connectionString
    'options'  => array('w' => 0, 'j' => 1), // @link http://www.php.net/manual/en/mongo.writeconcerns.php
    'default_collection' => 'logs'
);

/* End of file log_writer_mongo.php */
/* Location: .app/config/log_writer_mongo.php */