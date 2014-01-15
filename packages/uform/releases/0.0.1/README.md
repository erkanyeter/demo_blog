## Uform Class

This class allows you to create a completely working form without writing any html tag even those which are used to coordinate the form, so you just write code and then the class will automatically generate all HTML tags.
Uform uses the same Obullo helpers for form & inputs so it will not be something new to you.

You just write your php code to generate the form , then just call 'print' function where ever you want. The output will be formated by 'div' elements and 'css', which will give you the availabilty to to modify it easly.

<ul>
<li><a href='#createForm'>Create a Form</a></li>
<li><a href='#printTheForm'>Print The Form</a></li>
<li><a href='#styling'>Styling Form</a></li>
<li><a href='#validation'>Validation</a></li>
</ul>

### Initializing the Class

-------

```php
new Uform;
$this->uform->method();
```

### How To Use <a name='createForm'></a>

------

The main used method to create the form is 'create' function, within this function we will pass all the propereties and methods which will shape our form.
All parametes will be provided throw a closure function.

```php
<?php $this->uform->create(function(){ /* Form */ }); ?>
```

You will add the elements to the form as columns and group them into rows, so first you have to add a row then add columns into it. Each column equals a form element.
See the following example :

```php
<?php
$this->uform->create('table', function() {

    $this->addForm('/tutorials/hello_uform', array( 'method' => 'post' ) );

    $this->addRow();
    $this->setPosition('label', 'left');
    $this->addCol(array(
        'label' => 'Email',
        'rules' => 'required|xssClean',
        'input' => $this->input('email', $this->setValue('email'), ' id="email" ' ),
    ) );

    $this->addCol(array(
        'label' => 'Pass',
        'rules' => 'xssClean|minLen(5)|validEmail|required',
        'input' => $this->input('pass', $this->setValue('pass'), '', ' id="password" ' ),
    ) );

    $this->addRow();
    $this->setPosition('label', 'left'); // left - right -top
    $this->addCol(array(
        'label' => 'Gender',
        array('label' => 'Male', 'input' => $this->radio('gender', '1', $this->setValue('gender') ) ),
        array('label' => 'Female', 'input' => $this->radio('gender', '2', $this->setValue('gender') ) )
        , 'rules' => 'required|xssClean'
    ) );

    $this->addRow();
    $this->setPosition('label', 'left'); // left - right -top
    $this->addCol(array(
        'label' => 'Language',
        array('label' => 'Turkish', 'input' => $this->checkbox('lang', 'TR', $this->setValue('lang') ) ),
        array('label' => 'English', 'input' => $this->checkbox('lang', 'EN', $this->setValue('lang') ) ),
        array('label' => 'Arabic', 'input' => $this->checkbox('lang', 'AR', $this->setValue('lang') ) )
    ) );

    $this->addRow();
    $this->setPosition('label', 'left'); // left - right -top
    $this->addCol(array(
        'label' => 'First Select', 'input' => $this->dropdown('selectbox', array(1 => 'Yes', 2 => 'No', 3 => 'I don\'t know'), $this->setValue('selectbox') )
    ) );
    $this->addCol(array(
        'label' => 'Select 2', 'input' => $this->dropdown('selectbosdd', array(1 => 'Yes', 2 => 'No', 3 => 'I don\'t know') )
    ) );

    $this->addRow();
    $this->setPosition('label', 'left');
    $this->addCol(array(
        'label' => 'Multi Select', 
        'input' => $this->multiselect('selectboxd',
          array(1 => 'Yes', 2 => 'No', 3 => 'I don\'t know'),
          $this->setValue('selectboxd')),
        'rules' => 'required|xssClean'
    ) );


    $this->addRow();
    $this->setPosition('label', 'left');
    $this->addCol(array(
        'label' => 'Information', 'input' => $this->textarea('info', $this->setValue('info'))
    ) );

    $this->addRow();
    $this->setPosition('input', 'center');
    $this->addCol(array(
        'input' => $this->submit('dopost', ' Sign-up ', ' id="signup" '), 
        'rules' => 'required|xssClean'
    ) );
});
?>
```

First we added the form properties throw '$this->addForm'. Then we added a new row '$this->addRow', all the columns which will be added after it will be grouped in the in the same row unless you add another new row and start a new group.

<strong>addCol</strong> function accepts an array , this array declare the label of the column, the input field and the validation rules.
As we mentioned before that this class is using the same functions of Obullo form package.

'the following code must be in passed throw $this->addCol(array(/*here*/)) function' :

For Radios & Checkboxs you have to add a label for each checkbox or radio element, & a general label for the column ie :

```php
<?php
$this->addCol(array(
        'label' => 'Language',
        array( 'label' => 'Turkish', 'input' => $this->checkbox( 'lang', 'TR', $this->setValue( 'lang' ) ) ) ,
        array( 'label' => 'English', 'input' => $this->checkbox( 'lang', 'EN', $this->setValue( 'lang' ) ) ),
        array( 'label' => 'Arabic', 'input' => $this->checkbox( 'lang', 'AR', $this->setValue( 'lang' ) ) )
        )
    );
?>
```

### Print The Form <a name='printTheForm'></a>

------

After creating the form you just need to decide where to print the form and then call '$uform->printForm()' :

```php
<?php echo $this->uform->printForm() ?>
```

### Styling Form <a name='styling'></a>

------

Uform uses 'div' as a wrapper for all elements, so the 'row' is a 'division' , as well the column, label and the error message, moreover Uform uses css classes to coordinate the output, these css styles are written in uform.css, so this gives you the availability to update the output depending on your needs, howe its recommended to override the styles in a separate css file.
Example from uform.css :

```css
.uform-div-wrapper
{
  /*--*/
}

.uform-row
{
  /*--*/
}

.uform-column
{
  /*--*/
}

.uform-column input,
.uform-column select
{
  /*--*/
}
.uform-column input[type='submit']
{
  /*--*/
}

.uform-label-wrapper
{
  /*--*/
}
```

### Validation <a name='validation'></a>

------

After you set the rules for each column on the "creat function", you just have to run the validation. This will validate all the fields and return true or false :

```php
$this->uform->isValid();
```