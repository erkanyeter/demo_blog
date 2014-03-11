<?php
/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Database Variables

*/
$database = array(

    'db' => function () {       // or new Pdo_pgsql .. new Mongo_Db .. ;
        return new Pdo_Mysql(
            array(    
            'variable' => 'db',
            'hostname' => 'localhost',
            'username' => 'root',
            'password' => '12345',
            'database' => 'demo_blog',
            'driver'   => '',   // optional
            'prefix'   => '',
            'dbh_port' => '',
            'char_set' => 'utf8',
            'dsn'      => '',
            'options'  => array() // array( PDO::ATTR_PERSISTENT => false ); 
            )
        );
    },
);

/* End of file database.php */
/* Location: .app/config/debug/database.php */
