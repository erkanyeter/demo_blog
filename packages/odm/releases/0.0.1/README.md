## Odm (Object Database Model)<a name="object-database-model"></a>

### What is the Odm

Bye bye to traditional models which kill productivity of developers we create models <b>on the fly</b> using <b>Schemas</b>.

<kbd>Odm</kbd> is a model that does <kbd>validate</kbd> and do <kbd>crud</kbd> operations using your validation schemas.

### Creating a Schema

Schema is simply a class that contains your <b>labels</b>, <b>data types</b> and <b>validaton rules</b>. A schema class is located in your <kbd>schemas</kbd> folder and looks like below the example.

```php
<?php

$users = array(
  '*' => array('colprefix' => 'user_'),

  'id' => '',
  'email' => array(
    'label' => 'User Email',
    'types' => '_not_null|_varchar(255)',
    'rules' => 'required|minLen(6)|validEmail',
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
+ classes
+ packages
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
    'types' => 'minLen(6)|_varchar(160)',
    'rules' => 'required|validEmail'
    ),
  'password' => array(
    'label' => 'User Password',
    'types' => '_not_null|_varchar(255)',
    'rules' => 'required|minLen(6)'
    ),
  'cities' => array(
    'label' => 'Cities',
    'types' => '_enum|_default("Berlin")',
    'rules' => 'required|maxLen(20)'),
    '_enum' => array('London','Tokyo','Paris','New York','Berlin','Istanbul'),
);

```

### Creating Schemas Automatically ( Only Mysql )

When you <b>call a model</b>, <kbd>model package</kbd> creates automatically the schema file if it does not exists. 
Using your tablename the <kbd>schema_mysql</kbd> <b>package</b> parses your column information of your database table and it builds automatically the current validation rules.

If you provide the schema in array format schema driver also will create the database table if it does not exists.

**Note:** At this time we have just <b>Mysql Schema Driver</b>. if you want a write schema driver for other database types, please search on the net how to <b>submit a package</b> to Obullo.

### Model Reference

```php
new Model(string $var, mixed $schemaOrTable = '', string $dbVar = 'db');
```

* <b>First Parameter:</b> This parameter specifies the controller variable, you can access it like $this->var->method();
* <b>Second Parameter:</b> This parameter sets database tablename or schema array, if you provide an array it will convert to schema object.
* <b>Third Parameter:</b> This parameter sets the current database variable, default is "db".


### Creating & Loading Models

```php
new Model('user', 'users');
```
This code create a model on the fly and store it into <b>$this->user</b> variable. All models are empty classes and they extend to Odm Class automatically.


### Using Array Schema

```php
<?php
$userSchema['users'] = array(
            'email'    => array('label' => 'User Email', 'types' => '_varchar(160)', 'rules' => 'required|validEmail'),
            'password' => array('label' => 'User Password', 'types' => '_varchar(255)', 'rules' => 'required|minLen(6)')
        );

new Model('user', $userSchema);
```

The <b>key</b> of the $userSchema array ( in this example we use <kbd>users</kbd> ) sets the <b>tablename</b> of the schema.


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
?>
```

Then you can call your method.

```
<?php
$this->user->save();
?>
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
?>
```

Available <b>CRUD operations</b> that we are support listed below. You can define any of these methods.

* save
* insert
* update
* replace
* delete
* remove
* read
* callback_ ( form validation callback function prefix )


### Saving Data

Below the example we create a save function and call it on the fly.

```php
<?php
new Model('user', 'users');

$this->user->email = 'me@example.com';
$this->user->password = '123456';

$this->user->func('save',function() {
    return $this->db->insert('users', $this);
});

if($this->user->save())  // if save function success !
{
    echo 'User Saved.';
} 
else 
{
    print_r($this->user->errors());  // Gives validation errors !
    print_r($this->user->values());  // Gives filtered values !
}
```


### Validating Data

Using <b>isValid()</b> function you can control the validator object.

```php
<?php
new Model('user', 'users');

$this->user->email = 'me@example.com';
$this->user->password = '123456';

// Set extra rule for none db fields.
$this->user->setRules('confirm_password', array('label' => 'Confirm Password', 'rules' => 'required|matches[password]'));
$this->user->setRules('agreement', array('label' => 'User Agreement', 'rules' => 'isInteger|required'));

$this->user->func('save',function() {
    if ($this->isValid())  // isValid() function do validation using your schema.
    {
        $this->password = md5($this->values('password'));  // You can set again a value after the validation
        return $this->db->insert('users', $this);
    }
    return false;
});

if($this->user->save())  // if save function success !
{
    echo 'User Saved.';
} 
else 
{
    print_r($this->user->errors());  // Gives validation errors !
    print_r($this->user->values());  // Gives filtered values !
}
```

**Tip:** Please download the Obullo and discover the <kbd>tutorials/controller/hello_odm.php</kbd> page for more details.


### Updating Data 

Updating, deleting, saving and other crud operations are similar.

```
<?php
$this->user->email = 'me@example.com';

$this->user->func('save',function($id) {
        $this->db->where('id', $id);
        $this->db->update('users', $this);
});

$this->user->save(5);  // updated user id 5
?>
```
### Deleting Data 

```
<?php
$this->user->func('delete',function($id) {
        $this->db->where('id', $id);
        $this->db->delete('users', $this);
});

$this->user->delete(5);  // updated user id 5
?>
```

### Getting Validation Errors

<b>$this->user->values()</b> function gives you validation errors.

```php
<?php
if($this->user->save())
{
    // ...
}

print_r($this->user->errors());  //  gives all errors

/*
Array
(
    [user_password] => The Password field is required.
    [user_email] => The Email Address field is required.
    [success] => 0
)
*/

$this->user->errors('user_email');  // gives error of the user_email field
```

### Getting Validated Values

<b>$this->user->values()</b> function gives you filtered values.

```php
<?php
if($this->user->save())
{
    // ...
} 

print_r($this->user->values());  //  gives all validated values

/*
Array
(
    [user_id] => 0
    [user_password] => '',
    [user_email] => 'test@test.com'
)
*/

$this->user->values('user_email');  // gives value of the user_email field
```

### Getting Error Messages

<b>$this->user->messages()</b> function gives you the last error message with <b>error codes</b>.

```php
<?php
print_r($this->user->messages());
/*
Array
(
    [success] => 0
    [errorKey] => validationError
    [errorCode] => 10
    [errorString] => There are some errors in the form fields.
    [errorMessage] => There are some errors in the form fields.
)
*/
$this->user->messages('success'); // gives you value of the success. 
```

<b>$this->user->messages('key');</b> function gives you the value of the message. 

#### Description Of Keys

* <b>success</b> : If crud process success it returns to <b>1</b> otherwise <b>0</b>.
* <b>errorKey</b> : Every error has a related error string for readability of them.
* <b>errorCode</b> : Same as error keys you can use the error codes if you want.
* <b>errorString</b> : Gives the error message as string.
* <b>errorMessage</b> : Gives the translated error message using lingo() function. ( uses lingo package )

#### Description of Messages

<table>
<thead>
<tr>
<th>errorKey</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>validationError</td>
<td>There is an input validation error.</td>
</tr>
<tr>
<td>failure</td>
<td>User customized failure message produced by $this->model->setFailure(); method.</td>
</tr>
<tr>
<td>saveSuccess</td>
<td>Successful save operation.</td>
</tr>
<tr>
<td>insertSuccess</td>
<td>Successful insert operation.</td>
</tr>
<tr>
<td>updateSuccess</td>
<td>Successful update operation.</td>
</tr>
<tr>
<td>removeSuccess</td>
<td>Successful remove operation.</td>
</tr>
<tr>
<td>deleteSuccess</td>
<td>Successful delete operation.</td>
</tr>
<tr>
<td>replaceSuccess</td>
<td>Successful replace operation.</td>
</tr>
</tbody>
</table>

#### Description of Error Codes

<table>
<thead>
<tr>
<th>errorCode</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td>10</td>
<td>Validation Error</td>
</tr>
<tr>
<td>11</td>
<td>Successful</td>
</tr>
<tr>
<td>12</td>
<td>Failure</td>
</tr>
</tbody>
</table>


### Using Transactions

You can use the transactions if your table engine setted correctly as <b>INNODB</b>.

```php
<?php
$get = new Get;

$this->user->email    = $get->post('email');
$this->user->password = $get->post('password');
$this->user->setRules('agreement', array('label' => 'User Agreement', 'rules' => 'isInteger|required'));

$this->user->func('save',function() {
    if ($this->isValid()){
        $this->password = md5($this->values('password'));
        try
        {
            $this->db->transaction();
            $this->db->insert('users', $this);
            $this->db->commit();
        } 
        catch(Exception $e)
        {
            $this->db->rollBack();
            $this->setFailure($e->getMessage());  // Set rollback message to error messages.
            return false;
        }
        return true;
    }
    return false;
});

$this->user->save();  //  Call your save function.
```

### Mongodb Options

If you use mongo db package, crud library allows you to add mongo db write concerns if you provide them in array.

```php
$this->db->insert('users', array('w' => 0, 'j' => 1));
```

### Multiple Validation

For multiple saving operations just use your model in your foreach loop.

```php
<?php
new Model('user', 'users');

$this->user->func('save',function() { 
    if ($this->isValid())
    {
        $this->password = md5($this->values('password'));
        $this->db->insert('users', $this);
        return true;
    }
    return false;
});

$errors = array();
$users[] = array('email' => 'mahmut@gmail.com', 'password' => '');
$users[] = array('email' => 'hasan@gmail', 'password' => '');

foreach ($users as $row)
{
    $this->user->clear();  // you need to do clear for per request.    
    $this->user->email = $row['email'];
    $this->user->password = $row['password'];

    if($this->user->save())
    {
        // store success messages in to an array.
    } 
    else 
    {
        $errors[] = $this->user->errors();
    }
}

print_r($errors);
```

### Languages

You can use <b>lingo</b> package functionality in schema labels. 

```php
'email' => array('label' => 'lingo:Username', 'rules' => 'required|_string(60)|minLen(4)');
```

To run this functionality you need to load your language file using <b>$this->lingo->load();</b> function. Look at lingo package for more details.

### Callback Functions

```php
<?php

$users = array(
  '*' => array('_colprefix' => 'user_'),

  'id' => '',
  'email' => array(
    'label' => 'Username', 
    'types' => '_not_null|_varchar(60)',
    'rules' => 'required|callback_checkuser',
    ),
  'password' => array(
    'label' => 'Password', 
    'types' => '_varchar(255)',
    'rules' => 'required|minLen(6)',
    ),
);

/* End of file users.php */
/* Location: .app/schemas/users.php */
```

```php
<?php
new Url;
new Get;

new Model('user', 'users');

$this->user->username = $this->get->post('username');
$this->user->password = $this->get->post('password');

$this->user->func('callback_checkuser', function($username){
    if(strlen($username) > 10)
    {
        $this->setMessage('callback_checkuser', 'Username must be less than 10 characters.');
        return false;
    }
    return true;
});

$this->user->func('save',function() {
    if ($this->isValid())
    {
        $this->password = md5($this->values('password'));
        $this->db->insert('users', $this);
        return true;
    }
    return false;
});

if($this->user->save())
{
    $this->user->setNotice('User saved successfully.');
    $this->url->redirect('tutorials/form_html');
}
```

#### Http GET friendly Errors

Build Http GET friendly errors using query strings.

```php
<?php
echo $this->model->buildQueryErrors();

// output: errors[user_password]=Wrong%20Password!&errors['user_email']=Wrong%20Email%20Address!
```

#### Setting Custom Errors

You can set custom errors.

```php
$this->model->setError($field = '', $message = '');
```

#### Setting Messages

Sets session flashdata notice using current odm error status.

```php
$this->model->setNotice($message);
```
Gets current odm flashdata notice.

```php
$this->model->getNotice();
```

Failure messages allow to you set custom messages for unsuccessful operations e.g. ( Transactions RollBack Messages ).

```php
$this->model->setFailure($field = '', $message = '');
```

### Type Reference <a name="type-casting-reference"></a>

------

Type casting functions set your variables to the right type. The following is a list of all the type functions that are available to use:

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


### Rule Reference <a name="rule-reference"></a>

------

The following is a list of all the native rules that are available to use:

<table>
<thead>
<tr>
<th>Rule</th>
<th>Parameter</th>
<th>Description</th>
<th>Example</th>
</tr>
</thead>
<tbody>
<tr>
<td>required</td>
<td>No</td>
<td>Returns false if the form element is empty.</td>
<td></td>
</tr>
<tr>
<td>matches</td>
<td>Yes</td>
<td>Returns false if the form element does not match the one in the parameter.</td>
<td>matches(form_item)</td>
</tr>
<tr>
<td>minLen</td>
<td>Yes</td>
<td>Returns false if the form element is shorter then the parameter value.</td>
<td>minLen(6)</td>
</tr>
<tr>
<td>maxLen</td>
<td>Yes</td>
<td>Returns false if the form element is longer then the parameter value.</td>
<td>maxLen(12)</td>
</tr>
<tr>
<td>exactLen</td>
<td>Yes</td>
<td>Returns false if the form element is not exactly the parameter value.</td>
<td>exactLen(8)</td>
</tr>
<tr>
<td>alpha</td>
<td>No</td>
<td>Returns false if the form element contains anything other than alphabetical characters.</td>
<td></td>
</tr>
<tr>
<td>alphaNumeric</td>
<td>No</td>
<td>Returns false if the form element contains anything other than alpha-numeric characters.</td>
<td></td>
</tr>
<tr>
<td>alphaDash</td>
<td>No</td>
<td>Returns false if the form element contains anything other than alpha-numeric characters, underscores or dashes.</td>
<td></td>
</tr>
<tr>
<td>isDecimal</td>
<td>No</td>
<td>Returns false if the form element contains anything other than decimal characters.</td>
<td></td>
</tr>
<tr>
<td>isNumeric</td>
<td>No</td>
<td>Returns false if the form element contains anything other than numeric characters.</td>
<td></td>
</tr>
<tr>
<td>isInteger</td>
<td>No</td>
<td>Returns false if the form element contains anything other than an integer.</td>
<td></td>
</tr>
<tr>
<td>isNatural</td>
<td>No</td>
<td>Returns false if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.</td>
<td></td>
</tr>
<tr>
<td>isNaturalNoZero</td>
<td>No</td>
<td>Returns false if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.</td>
<td></td>
</tr>
<tr>
<td>validEmail</td>
<td>No</td>
<td>Returns false if the form element does not contain a valid email address.</td>
<td></td>
</tr>
<tr>
<td>validEmailDns</td>
<td>No</td>
<td>Returns false if the form element does not contain a valid email AND dns query return to false.</td>
<td></td>
</tr>
<tr>
<td>validEmails</td>
<td>Yes</td>
<td>Returns false if any value provided in a comma separated list is not a valid email. (If parameter true or 1 function also will do a dns query foreach emails)</td>
<td>validEmails(true)</td>
</tr>
<tr>
<td>validIp</td>
<td>No</td>
<td>Returns false if the supplied IP is not valid.</td>
<td></td>
</tr>
<tr>
<td>validBase64</td>
<td>No</td>
<td>Returns false if the supplied string contains anything other than valid Base64 characters.</td>
<td></td>
</tr>
<tr>
<td>noSpace</td>
<td>No</td>
<td>Returns false if the supplied string contains space characters.</td>
<td></td>
</tr>
<tr>
<td>callback_function(param)</td>
<td>Yes</td>
<td>You can define a custom callback function which is a class method located in your current model or just a function.</td>
<td>callback_functionname(param)</td>
</tr>
<tr>
<td>validDate</td>
<td>Yes</td>
<td>Returns false if the supplied date is not valid in current format. Enter your date format, default is mm-dd-yyyy.</td>
<td>validDate(yyyy-mm-dd)</td>
</tr>

</tbody>
</table>

### Prepping Reference <a name="prepping-reference"></a>

------

The following is a list of all the prepping functions that are available to use:

<table>
    <thead>
            <tr>
                <th>Name</th>
                <th>Parameter</th>
                <th>Description</th>
            </tr>
    </thead>
    <tbody>
            <tr>
                <td>xssClean</td>
                <td>No</td>
                <td>Runs the data through the XSS filtering function, described in the <kbd>Security Helper</kbd> package.</td>
            </tr>
            <tr>
                <td>prepForForm</td>
                <td>No</td>
                <td>Converts special characters so that HTML data can be shown in a form field without breaking it.</td>
            </tr>
            <tr>
                <td>prepUrl</td>
                <td>No</td>
                <td>Adds "http://" to URLs if missing.</td>
            </tr>
            <tr>
                <td>stripImageTags</td>
                <td>No</td>
                <td>Strips the HTML from image tags leaving the raw URL.</td>
            </tr>
            <tr>
                <td>encodePhpTags</td>
                <td>No</td>
                <td>Converts PHP tags to entities.</td>
            </tr>
    </tbody>
</table>


### Function Reference

------

#### $this->model->func('functionName', function(){});

Defines crud functions for your model.

#### $this->model->isValid();

Validate the schema using schema rules.

#### $this->model->getValidation();

Returns true if the model's schema validation success.

#### $this->model->messages();

Returns error codes, success and failure messages strings.

#### $this->model->getMessage($key = '');

Returns error codes, success and failure message strings.

#### $this->model->errors();

Returns all errors in array format.

#### $this->model->getError($field);

if you provide any fieldname it gives it's error

#### $this->model->setError($field, $error);

Sets custom error for the provided field.

#### $this->model->buildQueryErrors();

Builds Httpd GET friendly errors using query strings.

#### $this->model->setFailure($message);

Sets custom failure messages, you can use it when your transaction is fail.

#### $this->model->values();

Returns the <b>filtered secure</b> values of the inputs. 

#### $this->model->getValue($field = '');

Fetches the <b>filtered secure</b> value of the input. 

#### $this->model->output();

Returns all outputs of the model for debugging.