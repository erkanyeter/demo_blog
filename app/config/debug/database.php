<?php

/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Database Variables
|
*/
$database = array(

	'db' => new Pdo_Mysql(array(    // or new Mongo_Db;
		'variable' => 'db',
		'hostname' => 'localhost',
		'username' => 'root',
		'password' => '123',
		'database' => 'demo_blog',
		'driver'   => 'mysql',
		'prefix'   => '',
		'dbh_port' => '',
		'char_set' => 'utf8',
		'dsn' 	   => '',
		'options'  => array() // array( PDO::ATTR_PERSISTENT => false ); 
		)),
	
);

/* End of file database.php */
/* Location: .app/config/debug/database.php */