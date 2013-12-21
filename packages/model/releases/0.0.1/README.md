## Models <a name="models"></a>

Bye bye to traditional models which kill productivity of developers we create models <b>on the fly</b> using <b>Schemas</b>.

**Note:** In other words we use models in the controller section. The reading operations are <b>optional</b> .

**Note:** Look at <kbd>Odm Package</kbd> docs for more details.

### What is a Model? <a name="what-is-a-model"></a>

------

Models are PHP classes that are designed to work with information in your database. 

### Model Reference

```php
new Model(string $var, mixed $schemaOrTable = '', string $dbVar = 'db');
```

* <b>First Parameter:</b> Specifies the controller variable, you can access it like $this->var->method();
* <b>Second Parameter:</b> Sets database tablename or schema array, if you provide an array it will convert to schema object.
* <b>Third Parameter:</b> Sets the current database variable, default is "db".


### Creating & Loading Models

```php
new Model('user', 'users');
```
This code creates a model on the fly and stores it into <b>$this->user</b> variable. All models are empty classes and they extend to Odm Class automatically.

### Creating a Schema

Schema is a simply class that contains your <b>labels</b>, <b>data types</b> and <b>validaton rules</b>. A schema class is located in your <kbd>schemas</kbd> folder and looks like below the example.

```php
<?php

$users = array(
  '*' => array('colprefix' => 'user_'),

  'id' => '',
  'email' => array(
    'label' => 'User Email', 
    'types' => '_not_null|_varchar(60)',
    'rules' => 'required|validEmail',
    ),
  'password' => array(
    'label' => 'User Password', 
    'types' => '_not_null|_varchar(255)',
    'rules' => 'required|minLen(6)',
    ),
);

/* End of file users.php */
/* Location: .app/schemas/users.php */
```

### Using Array Data for Types

Some data types requires multiple values ( like _enum ) you can provide array data.

```php
<?php 

$users = array(
  '*' => array('colprefix' => 'user_'),

  'id' => '',
  'email' => array(
    'label' => 'User Email',
    'types' => '_not_null|_varchar(160)',
    'rules' => 'required|minLen(6)|validEmail'
    ),
  'password' => array(
    'label' => 'User Password', 
    'types' => '_not_null|_varchar(160)',
    'rules' => 'required|minLen(6)'
    ),
  'cities' => array(
    'label' => 'Cities',
    'types' => '_enum|_default("Paris")|',
    'rules' => 'required|maxLen(20)',
    '_enum' => array('London','Tokyo','Paris','New York','Berlin','Istanbul'),
    ),
);

```

##### Directory Structure

```php
+ app
    - schemas
        user.php
    + tasks
+ assets
+ classes
+ packages
```

### Creating Schemas Automatically ( Only Mysql )

When you <b>call a model</b>, <kbd>model package</kbd> creates automatically the schema file if it does not exist. 
Using your tablename the <kbd>schema_mysql</kbd> <b>package</b> parses your column information of your database table and it builds automatically the current validation rules.

If you provide the schema in array format schema driver also will create the database table if its not exists.

**Note:** At this time we have just <b>Mysql Schema Driver</b>. if you want a write schema driver for other database types, please search on the net how to <b>submit a package</b> to Obullo.


### Using Array Schema

```php
<?php
$userSchema['users'] = array(
            'email'    => array('label' => 'User Email', 'types' => '_varchar(160)', 'rules' => 'required|validEmail'),
            'password' => array('label' => 'User Password', 'types' => '_varchar(255)', 'rules' => 'required|minLen(6)')
        );

new Model('user', $userSchema);

// if database table not exists you can create it with create method using your schema.
$this->model->createTable();
```

### Creating Model Functions

After loading the model you need to build your model functions.

Use <b>$this->model->func();</b> method to build model functions.

```
<?php
$this->user->email = 'test@example.com';
$this->user->password = '123456';

$this->user->func('save',function() {
        return $this->db->insert('users', $this);
});
```

Then you can call your method.

```
<?php
$this->user->save();
```

This is the same as other crud operations.

```
<?php
$this->user->func('delete',function() {
        $this->db->where('id', 5);
        return $this->db->delete('users');
});
```

```
<?php
$this->user->delete();
```

Available <b>CRUD operations</b> that we recommend are listed below. You can define any of these methods.

* save
* insert
* update
* replace
* delete
* remove
* read

**Note:** Look at <kbd>Odm Package</kbd> docs for more details.

### Type Reference <a name="type-casting-reference"></a>

------

Type casting functions sets your variables to right type. The following is a list of all the type functions that are available to use:

<table>
    <thead>
            <tr>
                <th>Name</th>
                <th>Parameter</th>
                <th>Description</th>
                <th>Example</th>
            </tr>
    </thead>
    <tbody>
            <tr>
                <td>_array</td>
                <td>No</td>
                <td>Sets the schema field to array.</td>
                <td></td>
            </tr>
            <tr>
                <td>_binary</td>
                <td>Yes</td>
                <td>Sets the schema field to binary data.</td>
                <td>_binary or _binary(5)</td>
            </tr>
            <tr>
                <td>_bool</td>
                <td>No</td>
                <td>Sets the schema field to boolean.</td>
                <td></td>
            </tr>
            <tr>
                <td>_boolean</td>
                <td>No</td>
                <td>Sets the schema field to boolean.</td>
                <td></td>
            </tr>
            <tr>
                <td>_decimal</td>
                <td>Yes</td>
                <td>Sets the schema field to decimal number.</td>
                <td>_decimal(10,2)</td>
            </tr>
            <tr>
                <td>_double</td>
                <td>Yes</td>
                <td>Sets the schema field to decimal number.</td>
                <td>_double(5,1)</td>
            </tr>
            <tr>
                <td>_empty</td>
                <td>No</td>
                <td>Sets the schema field to ' '.</td>
                <td></td>
            </tr>
            <tr>
                <td>_enum</td>
                <td>Yes</td>
                <td>Sets the schema field to mixed.</td>
                <td>It takes parameter using schema array.</td>
            </tr>
            <tr>
                <td>_float</td>
                <td>Yes</td>
                <td>Sets the schema field to float number.</td>
                <td>_float(7,4)</td>
            </tr>
            <tr>
                <td>_int</td>
                <td>No</td>
                <td>Sets the schema field to integer number.</td>
                <td>int, int(4), int(5), int(9), int(11), int(20) ...</td>
            </tr>
            <tr>
                <td>_integer</td>
                <td>No</td>
                <td>Sets the schema field to integer number.</td>
                <td>Same as _int()</td>
            </tr>
            <tr>
                <td>_mixed</td>
                <td>No</td>
                <td>Keeps the native type of schema field.</td>
                <td>No</td>
            </tr>
            <tr>
                <td>_null</td>
                <td>No</td>
                <td>Sets the schema field to null data.</td>
                <td>No</td>
            </tr>
            <tr>
                <td>_number</td>
                <td>No</td>
                <td>Checks number type if its not number sets the schema field to 0.</td>
                <td>No</td>
            </tr>
            <tr>
                <td>_bit</td>
                <td>No</td>
                <td>Checks bit type if its not number sets the schema field to 0. Same as number.</td>
                <td>No</td>
            </tr>

            <tr>
                <td>_default</td>
                <td>Yes</td>
                <td>Sets the database field to default value.</td>
                <td>It takes parameter using schema array.</td>
            </tr>

            <tr>
                <td>_not_null</td>
                <td>No</td>
                <td>Sets _not_null data.</td>
                <td></td>
            </tr>

            <tr>
                <td>_unsigned</td>
                <td>No</td>
                <td>Accept only positive numbers.</td>
                <td>No</td>
            </tr>

            <tr>
                <td>_primary_key</td>
                <td>No</td>
                <td>Sets index ( PRIMARY KEY ) data.</td>
                <td>_primary_key</td>
            </tr>

            <tr>
                <td>_auto_increment</td>
                <td>No</td>
                <td>Sets _auto_increment data.</td>
                <td></td>
            </tr>

            <tr>
                <td>_key</td>
                <td>Yes</td>
                <td>Sets index ( KEY ) data.</td>
                <td>_key(name)(name) for multiple keys  _key(name)(last_name,first_name)</td>
            </tr>

            <tr>
                <td>_unique_key</td>
                <td>Yes</td>
                <td>Sets UNIQUE KEY.</td>
                <td>_unique_key(name)(name)  for multiple unique keys _key(name)(field1,field2)</td>
            </tr>

            <tr>
                <td>_foreign_key</td>
                <td>Yes</td>
                <td>Sets FOREIGN KEY.</td>
                <td>_foreign_key(users)(id)</td>
            </tr>

    </tbody>
</table>
