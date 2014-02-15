
/*
|--------------------------------------------------------------------------
| Database Configuration
|--------------------------------------------------------------------------
|
| Database Variables
|
*/
$database = array(

    'db' => new {database_driver}(array(    // new Pdo_Mysql // new Mongo_Db;
        'variable' => '{variable}', // db
        'hostname' => '{hostname}',
        'username' => '{username}',
        'password' => '{password}',
        'database' => '{database}',
        'driver'   => '{driver}',   // optional
        'prefix'   => '{prefix}',
        'dbh_port' => '{dbh_port}',
        'char_set' => '{char_set}', // utf8
        'dsn'      => '{dsn}',
        'options'  => array({options}) // array( PDO::ATTR_PERSISTENT => false ); 
        )),
    
);

/* End of file database.php */
/* Location: .app/config/debug/database.php */