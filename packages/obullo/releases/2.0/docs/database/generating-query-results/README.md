## Generating Query Results  <a name="generating-query-results"></a>

### Query Results

------

#### $this->db->result()

This function returns the query result as object.

#### $this->db->resultArray();

This function returns the query result as a pure array, or an empty array when no result is produced.

#### $this->db->row();

This function fetch one item and returns query result as object or false on failure.

#### $this->db->rowArray();

Identical to the above row() function, except it returns an array.

#### $this->db->count();

Returns to number of rows.


```php
$query = $this->db->query("YOUR QUERY");

if ($query->count() > 0)
{
   $row = $query->rowArray();

   echo $row['title'];
   echo $row['name'];
   echo $row['body'];
} 
```

#### $this->db->both()

Get column names and numbers.


```php
$query = $this->db->query("SELECT article_id FROM table");

$result = $query->both(); 
// output  Array ( [article_id] => 1 [0] => 1 )  
```


In addition, you can walk forward/backwards/first/last through your results using these variations:

* $row = $query->firstRow()
* $row = $query->lastRow()
* $row = $query->nextRow()
* $row = $query->previousRow()

By default they return an object unless you put the word "array" in the parameter:

* $row = $query->firstRow(assoc)
* $row = $query->lastRow(assoc)
* $row = $query->nextRow(assoc)
* $row = $query->previousRow(assoc)

### Testing for Results

------
If you run queries that might not produce a result, you are encouraged to test for a result first using the **row()** and **prepare** function:

```php
$query = $this->db->prep()  // pdo prepare() switch 
->where('ip_address', '127.0.0.1')
->get('ob_sessions')    // from this table
->exec();

if($query->row())
{
    $query = $query->exec();  // get cached query..
    $b = $query->resultArray();

    print_r($b);    // output array( ... )   
}
```

If **rowCount()** function available in your db driver you can use it ..

```php
$query = $this->db->where('ip_address', '127.0.0.1')->get('ob_sessions');

if($query->rowCount() > 0)
{
    $b = $query->resultArray();

    print_r($b);    // output array( ... )   
}
```

### Alternative PDO Query Results

------

If you want traditional PDO approach you can use **fetch** and **fetchAll** methods instead of standart query result functions. 

#### $this->db->fetch(int $fetch_style, int $cursor_orientation = ' ', int $cursor_offset = ' ')

Use this function to fetch **one item**.

```php
$query = $this->db->query(" ... ");

$result = $query->fetch(ASSOC);

 // output array( .. ) 
```

By default all fetch functions return an object unless you put the word **ASSOC** in the parameter.

```php
$query = $this->db->query(" ... ");

$result = $query->fetch();

 // output object( .. ) 
```

#### $this->db->fetchAll(int $fetch_style, int column index = 0, array ctor_args = array())


Use this function to fetch **all items**.

```php
$query = $this->db->query(" ... ");

$result = $query->fetchAll(ASSOC);

// output array( [0]=> array( 'field' => '', field2=> '' ), [1] => array(), ..)  
```

You can change the result type like this

```php
$query = $this->db->query(" ... ");

$result = $query->fetchAll();    // default object 

// output Array ( [0] => stdClass Object ( 'field' => '', ..) , [1] => stdClass Object ( )  ... )   
```

#### Result Types (Result Constants)

------
    
Below the table show available result types.

<table>
    <thead>
        <tr>
            <th>Function</th>    
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ASSOC</td>
            <td>Fetch query result as an associative array</td>
        </tr>
        <tr>
            <td>OBJ</td>
            <td>Fetch query result as object</td>
        </tr>
        <tr>
            <td>LAZY</td>
            <td>Fetch each row as an object with variable names that correspond to the column names.</td>
        </tr>
        <tr>
            <td>NAMED</td>
            <td>Fetch each row as an array indexed by column name</td>
        </tr>
        <tr>
            <td>NUM</td>
            <td>Fetch each row as an array indexed by column number</td>
        </tr>
        <tr>
            <td>BOTH</td>
            <td>Fetch each row as an array indexed by both column name and numbers</td>
        </tr>
        <tr>
            <td>BOUND</td>
            <td>Specifies that the fetch method shall return true and assign the values of the columns in the result set to the PHP variables to which they were bound with the PDO bindParam() or PDO bindColumn() methods.</td>
        </tr>
        <tr>
            <td>COLUMN</td>
            <td>Specifies that the fetch method shall return only a single requested column from the next row in the result set</td>
        </tr>
        <tr>
            <td>AS_CLASS</td>
            <td>Specifies that the fetch method shall return a new instance of the requested class, mapping the columns to named properties in the class.</td>
        </tr>
        <tr>
            <td>INTO</td>
            <td>Specifies that the fetch method shall update an existing instance of the requested class, mapping the columns to named properties in the class.</td>
        </tr>
        <tr>
            <td>KEY_PAIR</td>
            <td>Fetch into an array where the 1st column is a key and all subsequent columns are value. Note: Available since PHP 5.2.3</td>
        </tr>
        <tr>
            <td>CLASS_TYPE</td>
            <td>Determine the class name from the value of first column.</td>
        </tr>
        <tr>
            <td>SERIALIZE</td>
            <td>As into constant but object is provided as a serialized string.</td>
        </tr>
        <tr>
            <td>PROPS_LATE</td>
            <td>Note: Available since PHP 5.2.3</td>
        </tr>
        <tr>
            <td>FUNC</td>
            <td></td>
        </tr>
        <tr>
            <td>GROUP</td>
            <td></td>
        </tr>
        <tr>
            <td>UNIQUE</td>
            <td></td>
        </tr>
        <tr>
            <td>ORI_NEXT</td>
            <td>Fetch the next row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_PRIOR</td>
            <td>Fetch the previous row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_FIRST</td>
            <td>Fetch the first row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_LAST</td>
            <td>Fetch the last row in the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_ABS</td>
            <td>Fetch the requested row by row number from the result set. Valid only for scrollable cursors.</td>
        </tr>
        <tr>
            <td>ORI_REL</td>
            <td>Fetch the requested row by relative position from the current position of the cursor in the result set.</td>
        </tr>
    </tbody>
</table>

More details at [PDO Predefined Constants](http://php.net/manual/en/pdo.constants.php).


#### $this->db->rowCount()

Returns the number of rows affected by the execution of the last INSERT, DELETE, or UPDATE statement.

The most popular PDO database drivers like **MySQL** support to **rowCount();** function for SELECT statement but some database drivers does not support rowCount() function like **SQLite**.If you develop a portable applications **do not use** rowCount(); function via **SELECT** statements.

```php
$query = $this->db->query("INSERT UPDATE DELETE QUERY");
$result = $query->rowCount(); 
```

```php
$query = $this->db->query("INSERT INTO articles (title, article) VALUES('test..','blabla..')");
echo $this->db->rowCount();  // output 1
```

CRUD class already return to affected rows not necassary to use rowCount();.

```php
$data['title']   = 'row count test';
$data['article'] = 'blablabla ...';

$affected_rows = $this->db->insert('articles', $data);
echo $affected_rows;  // output 1
```

If your Pdo driver **does not** support **rowCount()**, to finding number of rows for the **SELECT** statement you can use native sql COUNT(*)

```php
echo $this->db->select("COUNT(*) as num")->get('articles')->row()->num; // output 7

// or 

$query = $this->db->query("SELECT COUNT(*) as num FROM articles");
echo $query->row()->num; // output 7
```

An alternative way If data is not large and you already use fetchAll then you can use php count(); function

```php
$query = $this->db->query("SELECT * FROM articles");
$a = $query->resultArray();

echo count($a);   // output 7
```

### Fetching Column Names

------

This is an example for fetching column names.

```php
$query = $this->db->query("SELECT * FROM articles");
$a = $query->fetch(ASSOC);

print_r(array_keys($a)); 

// Array ( [0] => article_id [1] => title [2] => article [3] => link [4] => creation_date )
```

#### $this->db->fetchColumn(int 'col number')

For example you have a table like this and you want to fetch value of one column.

```php
// column numbers
Col no: 0           1           2           3           4
 _ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __
|                                                                |
| article_id  |   title  |  article     |  link  | creation_date |
 __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ _
|             |          |              |        |               |
|     1       |  hello   |  blabla      |        | 2009-02-10    |
|             |          |              |        |               |
|     2       |  bonjour |  hello world |        | 2009-03-10    |
|             |          |              |        |               |
|     3       |  selam   |  selam dunya |        | 2009-04-10    |
| __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ |
```

I want to get values of column number 1 so code is here

```php
$query = $this->db->query("SELECT * FROM articles");

echo $query->fetchColumn(1).'<br />';
echo $query->fetchColumn(1).'<br />';
echo $query->fetchColumn(1).'<br />';
```

Now you want to fetch values of column number **1** and **2** , to getting multiple values we should use **PDO query caching** functionality.

```php
$this->db->prep();   // tell to db class use pdo prepare
$this->db->query("SELECT * FROM articles");

$query = $this->db->exec();

echo $query->fetchColumn(1).'<br />';  // hello
echo $query->fetchColumn(1).'<br />';  // bonjour
echo $query->fetchColumn(1).'<br />';  // selam

echo '<br />';

$query = $this->db->exec();  // run cached query SELECT * FROM articles ..

echo $query->fetchColumn(2).'<br />';  // blabla
echo $query->fetchColumn(2).'<br />';  // hello world
echo $query->fetchColumn(2).'<br />';  // selam dunya
```

Now we have multiple column values and we build it very fast ..

**Note:** This pdo function especially designed for fetching a single column in the next row of a result set. 