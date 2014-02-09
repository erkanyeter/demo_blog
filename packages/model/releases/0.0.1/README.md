## Models <a name="models"></a>

Bye bye to traditional models which kill productivity of developers we create models <b>on the fly</b>. Models are simply designed  to validating your database schemas.

### What is a Model? <a name="what-is-a-model"></a>

------

Models are PHP classes that are designed to work with information in your database. 

### Model Reference

```php
new Model(string $var, mixed $schemaOrTable = '', string $dbVar = 'db');
```

* <b>First Parameter:</b> Specifies the controller variable, you can access it like $this->var->method().
* <b>Second Parameter:</b> Sets database tablename.
* <b>Third Parameter:</b> Sets the current database variable, default is "db".


### Creating & Loading Models

```php
new Model('user', 'users');
```
This code creates a model on the fly and stores it into <b>$this->user</b> variable. All models are empty classes and they extend to Odm Class automatically.

### Creating a Schema

Schema is a simply config file that contains your <b>labels</b>, <b>data types</b> and <b>validaton rules</b>. A schema file is located in your <kbd>schemas</kbd> folder and looks like below the example.

```php
<?php

$users = array(
  '*' => array(),

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
<kbd>Schema_mysql</kbd> <b>package</b> parses your column information of your database table and it builds automatically the current data types.

**Note:** At this time we have just <b>Mysql Schema Driver</b>.


### Creating Model

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

**Note:** Look at <kbd>Odm Package</kbd> docs for more details.

### Type Reference <a name="type-casting-reference"></a>

------

Type casting functions set your variables to right type. The following is a list of all the type functions that are available to use:

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


### Schema Join & Save

If you have more than one validation for two or more tables you can merge schema files in the same form.

Using dot "." in your field "key" you can join them. e.g. **$this->user->data['second_tablename.fieldname']**

```php
<?php

new Model('order', 'orders');

$this->order->data['order_type']        = '2';   
$this->order->data['order_description'] = 'blablabla';
$this->order->data['invoices.inovice_email_address'] = 'test@example.com';

$this->order->func('insert',function() {
        $this->db->insert('orders', $this);
        $this->db->insert('invoices', $this);
        return true;
});

if($this->order->insert())
{
    $this->form->setNotice('Order inserted successfully !', SUCCESS);
    $this->url->redirect('/home');
}
```

Above the operation will insert data to orders and invoices tables and also does validation on each of them.

### Form Model ( No Schema )

```php
<?php

new Model('user', false);   // File schema disabled
                            // User model will use form object for the validation.

if(isset($_POST['dopost'])) // if button click !
{
    $this->form->setRules('user_email', 'Email', 'required|validEmail');
    $this->form->setRules('user_password', 'Password', 'required|callback_password');

    $this->user->func('callback_password', function(){
        if($_POST['user_password'] != '123'){
            $this->setMessage('callback_password', 'Password not correct.');
            return false; // wrong password
        }
        return true;
    });

    $this->user->isValid(); // do validation

    print_r($this->user->getOutput());
}

/* End of file hello_form_model.php */
/* Location: .public/tutorials/controller/hello_form_model.php */
```

**Note:** Look at <kbd>Odm Package</kbd> docs for more details.
