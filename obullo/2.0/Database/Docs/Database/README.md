
## Database

<ul>
<li><a href="#requirements">Server Requirements</a></li>
<li><a href="#connection">Connection</a>
    <ul>
        <li><a href="#choosing-driver">Choosing Database Driver</a></li>
        <li><a href="#supported-types">Supported Database Types</a></li>
        <li><a href="#explanation-of-values">Explanation of Values</a></li>
        <li><a href="#options-parameter">Options Parameter</a></li>
        <li><a href="#connection-tutorial">Connection Tutorial</a></li>
    </ul>    
</li>
<li><a href="#generating-query-results">Generating Query Results</a></li>     
<li><a href="#running-queries">Running And Escaping Queries</a></li>     
<li><a href="#query-binding">Query Binding</a></li>
<li><a href="#query-helper-functions">Query Helper Functions</a></li>
<li><a href="#transactions">Transactions</a></li>
</ul>

### Server Requirements <a name='requirements'></a>

------

Database class use <strong>PDO</strong> for database operations.

<strong>Mysql</strong> and <strong>SQLite</strong> drivers is enabled by default. If you want to use another Database driver you must enable related PDO Driver from your php.ini file.

Un-comment the PDO database file pdo.ini

```php
extension=pdo.so
```

and un-comment your driver file pdo_mysql.ini

```php
extension=pdo_mysql.so
```

Look at for more details http://www.php.net/manual/en/pdo.installation.php

## Connection

### Choosing Database Driver <a name='choosing-driver'></a>

Open <kbd>services.php</kbd> in your root and set your <u>Database Driver</u> like below the example example ( Default Mysql ).


```php
/*
|--------------------------------------------------------------------------
| Db
|--------------------------------------------------------------------------
*/
$c['db'] = function () use ($c) {
    return $c['app']->db = new Obullo\Database\Pdo\Mysql($c['config']['database']);
};
```

To set your database configuration edit your <kbd>app/config/env/local/config.php</kbd>.

```php
/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/
'database' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '123456',
        'database' => 'demo_blog',
        'driver'   => '',   // optional
        'prefix'   => '',
        'dbh_port' => '',
        'char_set' => 'utf8',
        'dsn'      => '',
        'options'  => array() // array( PDO::ATTR_PERSISTENT => false ); 
),
```

### Supported Database Types <a name='supported-types'></a>

------

<table class="span9">
<thead>
<tr>
<th>PDO Driver Name</th>
<th>Connection Name</th>
<th>Database Name</th>
</tr>
</thead>
<tbody>
<tr>
<td>PDO_DBLIB</td>
<td>dblib / mssql / sybase / freetds</td>
<td>FreeTDS / Microsoft SQL Server / Sybase</td>
</tr>
<tr>
<td>PDO_FIREBIRD</td>
<td>firebird</td>
<td>Firebird/Interbase 6</td>
</tr>
<tr>
<td>PDO_IBM</td>
<td>ibm / db2</td>
<td>IBM DB2</td>
</tr>
<tr>
<td>PDO_MYSQL</td>
<td>mysql</td>
<td>MySQL 3.x/4.x/5.x</td>
</tr>
<tr>
<td>PDO_OCI</td>
<td>oracle / (or alias oci)</td>
<td>Oracle Call Interface</td>
</tr>
<tr>
<td>PDO_ODBC</td>
<td>odbc</td>
<td>ODBC v3 (IBM DB2, unixODBC and win32 ODBC)</td>
</tr>
<tr>
<td>PDO_PGSQL</td>
<td>pgsql</td>
<td>PostgreSQL</td>
</tr>
<tr>
<td>PDO_SQLITE</td>
<td>sqlite / sqlite2 / sqlite3</td>
<td>SQLite 3 and SQLite 2</td>
</tr>
<tr>
<td>PDO_4D</td>
<td>4d</td>
<td>4D</td>
</tr>
<tr>
<td>PDO_CUBRID</td>
<td>cubrid</td>
<td>Cubrid</td>
</tr>
</tbody>
</table>

Framework has a config file that lets you store your database connection values (username, password, database name, etc.). The config file is located in:

<kbd>app/config/env/local/config.php</kbd>

If you want to add a second or third database connection <strong>copy/paste</strong> above the settings and change the <strong>'database'</strong> key name of your version you have choosen.

```php
'database' => array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'example_db',
    'driver'   => 'mysql',
    'prefix'   => '',
    'dbh_port' => '',
    'char_set' => 'utf8',
    'dsn'      => '',
    'options'  => array()
    ),
),
'db_analytics' => array(            // another database configuration
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'example_db',
    'driver'   => 'mysql',
    'prefix'   => '',
    'dbh_port' => '',
    'char_set' => 'utf8',
    'dsn'      => '',
    'options'  => array()
    ),
),
```

If you want to add <strong>dsn</strong> connection you don't need to provide some other parameters like this..

```php
'db' => array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'example_db',
    'driver'   => 'mysql',
    'prefix'   => '',
    'dbh_port' => '',
    'char_set' => 'utf8',
    'dsn'      => "mysql:host=localhost;port=3307;dbname=test_db;username=root;password=1234;",
    'options'  => array()
    ),
```

#### Explanation of Values <a name='explanation-of-values'></a>

* hostname - The hostname of your database server. Often this is "localhost".
* username - The username used to connect to the database.
* password - The password used to connect to the database.
* database - The name of the database you want to connect to.
* dbdriver - The database type. ie: mysql, postgres, odbc, etc. Must be specified in lower case.
* dbh_port - The database port number.
* char_set - The character set used in communicating with the database.
* dsn - Data source name.If you want to use dsn, you will not need to supply other parameters.
* options - Pdo set attribute options.

<strong>Note:</strong> Depending on what database platform you are using (MySQL, Postgres, etc.) not all values will be needed. For example, when using SQLite you will not need to supply a username or password, and the database name will be the path to your database file. The information above assumes you are using MySQL.

#### Options Parameter  <a name='options-parameter'></a>

There is a global <strong>PDO options</strong> parameter in your database configuration. You can <strong>set connection attributes</strong> for each connection. if you want to <strong>Persistent Connection</strong> you can do it like.

```php
'options'  => array( PDO::ATTR_PERSISTENT => true );
```
You can add more attributes in your option array like this.

```php
'options' => array( PDO::ATTR_PERSISTENT => false , PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true );
```

<strong>Tip:</strong>You can learn more details about PDO Predefined Constants.


### Connection Tutorial <a name='connection-tutorial'></a>

------

Putting this code into your Controller enough for the current database connection.

```php
<?php
/**
 * $app hello_world
 * @var Controller
 */
$app = new Controller(
    function () {
        global $c;
        $c['view'];
        $c['db'];   // create a database connection
    }
);

$app->func(
    'index', 
    function () {

        $this->db->query('SELECT * FROM users');  // $this->db = configured to database object from your services.php
        $results = $this->db->getResultArray();

        print_r($results);
    }
);

/* End of file hello_world.php */
/* Location: .public/tutorials/controller/hello_world.php */
```

### Generating Query Results  <a name="generating-query-results"></a>

------

#### $this->db->getResult()

This function returns the query result as object.

#### $this->db->getResultArray();

This function returns the query result as a pure array, or an empty array when no result is produced.

#### $this->db->getRow();

This function fetches one item and returns query result as object or false on failure.

#### $this->db->getRowArray();

Identical to the above row() function, except it returns an array.

#### $this->db->rowCount();

Count number of rows and returns to integer value.

#### $this->db->count();

Count number of rows and returns to integer value if "count() == 0" then returns to false.

#### $this->db->result();

Alias of getResult();

#### $this->db->resultArray();

This function same as getResultArray() except <b>returns to empty array</b> if result false.

#### $this->db->row();

Alias of getRow();

#### $this->db->rowArray();

This function same as getRowArray() except <b>returns to empty array</b> if result false.

#### $this->db->lastQuery();

Returns to last executed sql string.


## Testing Results


```php
$this->db->query('YOUR QUERY');

if ($this->db->count() > 0) {

   $row = $this->db->getRowArray();

   echo $row['title'];
   echo $row['name'];
   echo $row['body'];
} 
```

## Testing Results with Crud

------

```php
$c['crud'];

$this->db->where('user_id', 5)->get('users');

if ($this->db->count() !== false) {

    $b = $this->db->getResultArray();

    print_r($b);    // output array( ... )   
}
```

## Running and Escaping Queries <a name="running-queries"></a>

### Direct Query

------

To submit a query, use the following function:

```php
$this->db->query('YOUR QUERY HERE');
```

The <dfn>query()</dfn> function returns a database result **object** when "read" type queries are run, which you can use to show your results. When retrieving data you will typically assign the query to your own variable, like this:

```php
$query = $this->db->query('YOUR QUERY HERE');
```

### Exec Query

------

This query type same as direct query just it returns the $affected_rows automatically. You should use **execQuery** function for INSERT, UPDATE, DELETE operations.

```php
$affected_rows = $this->db->exec('INSERT QUERY'); 

echo $affected_rows;   //output  1
```

### Escaping Queries

------

It's a very good security practice to escape your data before submitting it into your database. Obullo has three methods that help you do this:

#### $this->db->escape()

This function determines the data type so that it can escape only string data. It also automatically adds single quotes around the data and it can automatically determine data types. 

```php
$sql = "INSERT INTO table (title) VALUES(".$this->db->escape((string)$title).")";
```

Supported data types: <samp>(int), (string), (boolean)</samp>

#### $this->escapeStr();

This function escapes the data passed to it, regardless of type. Most of the time you'll use the above function rather than this one. Use the function like this:

```php
$sql = "INSERT INTO table (title) VALUES('".$this->db->escapeStr($title)."')";
```

#### $this->db->escapeLike()

This method should be used when strings are to be used in LIKE conditions so that LIKE wildcards ('%', '_') in the string are also properly escaped. 

```php
$search = '20% raise';<br />
$sql = "SELECT id FROM table WHERE column LIKE '%".$this->db->escapeLike($search)."%'";
```

**Note:** You don't need to **$this->escapeLike** function when you use Crud class because of active record(CRUD) class use auto escape for like conditions.

```php
$this->db->select("*");
$this->db->like('article','%%blabla')
$this->db->orLike('article', 'blabla')
$query = $this->db->get('articles');

echo $this->db->lastQuery();

// Output
```

## Query Binding <a name="query-binding"></a>

------

Framework offers PDO bindValue functionality, using query binding will help you for the performance and security:

#### Bind Types

<table>
    <thead>
        <tr>
            <th>Friendly Constant</th>
            <th>PDO CONSTANT</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>PARAM_BOOL</td>
            <td>PDO::PARAM_BOOL</td>
            <td>Boolean</td>
        </tr>
        <tr>
            <td>PARAM_NULL</td>
            <td>PDO::PARAM_NULL</td>
            <td>NULL</td>
        </tr>
        <tr>
            <td>PARAM_INT</td>
            <td>PDO::PARAM_INT</td>
            <td>String</td>
        </tr>
        <tr>
            <td>PARAM_STR</td>
            <td>PDO::PARAM_STR</td>
            <td>Integer</td>
        </tr>
        <tr>
            <td>PARAM_LOB</td>
            <td>PDO::PARAM_LOB</td>
            <td>Large Object Data (LOB)</td>
        </tr>
    </tbody>
</table>

The **double dots** in the query are automatically replaced with the values of **bindValue** functions.

#### Bind Example

##### $this->db->bindValue($paramater, $variable, $type)

```php
$this->db->prepare("SELECT * FROM articles WHERE article_id=:id OR tag=:tag");

$this->db->bindValue(':id', 1, PARAM_INT);  
$this->db->bindValue(':tag', 'php', PARAM_STR); 
$this->db->execute();

$a = $this->db->getResultArray(); 
print_r($a);
```

The **double dots** in the query are automatically replaced with the values of **bindValue** methods.

**Note:**  The secondary benefit of using binds is that the values are automatically escaped, producing safer queries. You don't have to remember to manually escape data; the engine does it automatically for you.

### Question Mark Binding

```php
$this->db->prepare("SELECT * FROM articles WHERE article_id = ? OR tag = ?");

$this->db->bindValue(1, 1, PARAM_INT);  
$this->db->bindValue(2,'php', PARAM_STR); 
$this->db->execute();

$a = $this->db->getResult(); 
var_dump($a);
```

### Array Binding

```php
$this->db->prepare("SELECT * FROM articles WHERE article_id = ? OR tag = ?");
$this->db->execute(array(1, 'php'));

$a = $this->db->getResult(); 
var_dump($a);
```

## Query Helper Functions<a name="query-helper-functions"></a>

------

#### $this->db->insertId()

The insert ID number when performing database inserts.

#### $this->db->getDrivers()

Outputs the database platform you are running (MySQL, MS SQL, Postgres, etc...):

```php
$drivers = $this->db->getDrivers();   print_r($drivers);  // Array ( [0] => mssql [1] => mysql [2] => sqlite2 )
```
 
#### $this->db->getVersion()

Outputs the database version you are running (MySQL, MS SQL, Postgres, etc...):

```php
echo $this->db->getVersion(); // output like 5.0.45 or returns to null if server does not support this feature..
```

#### $this->db->isConnected()

Checks the database connection is active or not

```php
echo $this->db->isConnected(); // output 1 or 0
```

#### $this->db->lastQuery();

Returns the last query that was run (the query string, not the result). Example:

```php
$str = $this->db->lastQuery();
```

#### $this->db->setAttribute($key, $val);

Sets PDO connection attribute.

```php
$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

$this->db->query(" .. ");

print_r($this->db->errorInfo());  // handling pdo errors

$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // restore error mode
```

#### $this->db->errorInfo();

Gets the database errors in pdo slient mode instead of getting pdo exceptions.

The following two functions help simplify the process of writing database INSERT s and UPDATE s.

## Database Transactions <a name="database-transactions"></a>

### Transactions

------

Database abstraction allows you to use transactions with databases that support transaction-safe table types. In MySQL, you'll need to be running <b>InnoDB</b> or <b>BDB</b> table types rather than the more common MyISAM. Most other database platforms support transactions natively.

If you are not familiar with transactions we recommend you find a good online resource to learn about them for your particular database. The information below assumes you have a basic understanding of transactions.


### Running Transactions <a name=""></a>

------

To run your queries using transactions you will use the <kbd>$this->db->transaction()</kbd>, <kbd>$this->db->commit()</kbd> and <kbd>$this->db->rollBack()</kbd> methods as follows:

```php
try {
    
    $this->db->transaction(); // begin the transaction
    
    // INSERT statements
    
    $this->db->exec("INSERT INTO persons (person_type, person_name) VALUES ('lazy', 'ersin')");
    $this->db->exec("INSERT INTO persons (person_type, person_name) VALUES ('clever', 'john')");
    $this->db->exec("INSERT INTO persons (person_type, person_name) VALUES ('funny', 'bob')");

    $this->db->commit();    // commit the transaction

    echo 'Data entered successfully<br />'; // echo a message to say the database was created

} catch(Exception $e)
{    
    $this->db->rollBack();       // roll back the transaction if we fail
       
    echo $e->getMessage();  // echo exceptional error message
}
```

You can run as many queries as you want between the transaction/commit functions and they will all be committed or rolled back based on success or failure of any given query.

### Running Transactions with CRUD Class

------

Also you use active record class like this

```php
try {
    
    $this->db->transaction(); // begin the transaction
    
    // INSERT statements
        
    $this->db->insert('persons', array('person_type' => 'lazy', 'person_name' => 'ersin'));
    $this->db->insert('persons', array('person_type' => 'clever','person_name' => 'john'));
    $this->db->insert('persons', array('person_type' => 'funny','person_name' => 'bob'));

    $this->db->commit();    // commit the transaction

    echo 'Data entered successfully<br />'; // echo a message to say the database was created

} catch(Exception $e)
{    
    $this->db->rollBack();       // roll back the transaction if we fail
       
    echo $e->getMessage();  // echo exceptional error message
}
```