## Translator Class

The Translator Class provides functions to retrieve language files and lines of text for purposes of internationalization.

In your app folder you'll find one called translator containing sets of language files. You can create your own language files as needed in order to display error and other messages in other languages.

**Note:** Each language file must be stored in its own folder. For example, the English files are located at: <kbd>app/translations/en_US</kbd>

### Create Your Translation File

------

Within the file you will assign each line of text to an array called <var>$translate</var> with this prototype:

```php
$translate['language_key'] = "The actual message to be shown";
```

**Note:** It's a good practice to use actual message as key for all messages in a given file to avoid collisions with similarly named items in other files. 

```php
$translate['You must submit an email address'] = "You must submit an email address";
$translate['You must submit a URL']  = "You must submit a URL";
$translate['You must submit a username'] = "You must submit a username";
```

### Defining Translation Constants

We define the constants to control the translate keys.

```php
$translate[i18n_Form_Error::EMAIL_ADDRESS_REQUIRED] = "You must submit an email address";
$translate[i18n_Form_Error::USERNAME_REQUIRED] = "You must enter your username";
$translate[i18n_Form_Error::PASSWORD_REQUIRED] = "You must enter your password";
```

Translation <b>constants</b> located in your <b>app/i18n/</b> folder.

```php
-  app
    + config
    - i18n
        - Form
            Error.php
            Notice.php
        - Body
        - Head
```

An Example Constant class

```php
Class I18n_Form_Error
{
    const EMAIL_ADDRESS_REQUIRED = 'form_error_email_address_required';
    const USERNAME_REQUIRED      = 'form_error_username_required';
    const PASSWORD_REQUIRED      = 'form_error_password_required';
    const INVALID_EMAIL_ADDRESS  = 'form_error_invalid_email_address';
}
```
 
**Note**: I18n classes which are located in your <dfn>app/classes/i18n</dfn> folder manage constants of your language files.

### Loading a Translate File

------

If you want load language files from your <b>app</b> folder create your language files to there ..

```php
-  app
    + config
    - translations
        - en_US
            email.php
            contact.php 
```

This function loads a language file from your <kbd>app/translator</kbd> folder.


```php
$this->translator->load('filename');  // load translator file
```

Where <samp>filename</samp> is the name of the file you wish to load (without the file extension), and language is the language set containing it (ie, en_US).

```php
$['translator'];

$this->translator->load('welcome');
$this->translator['Welcome to the our site !'];
```

### Loading the Framework Files

Some of the packages use framework language file which is located in your <kbd>app/translations</kbd> folder. You can change the default language. ( look at <kbd>app/config/debug/config.php</kbd> ) 

Core packages will load framework language files which are located in <kbd>app/translations/$language</kbd> folder.

------

```php
-  app
    + config
    - translations
        - en_US
             date.php
             validator.php
            ...
        - es_ES
             date.php
             validator.php
            ...
```

This function loads the <b>date</b> language file from your <kbd>app/translator/es_ES</kbd> folder.

```php
$this->translator->load('date'); 
```

### Fetching a Line of Text

------

Once your desired language file is loaded you can access any line of text using this function:

```php
$this->translator['language_key'];
```
$this->translator class array access function returns the translated line if language line exists in your file, otherwise it returns to default text that you are provide.

### Checking a Translate Key of Text

Tramslate class allow to you array access and it returns to false if translate key not exists.

```php
if ( ! $this->translator['language_key'])) {
    echo 'language_key doest not exists.';
}
```

Checking none exist key.

```php
var_dump($this->translator['asdasdas']);  //  gives false ( boolean )
```

Printing none exist key.

```php
echo $this->translator['asdasdas'];      //  gives 'asdasdas' ( string )
```

### Using $this->translator->sprintf($key, $arguments , , , ... );

Translator class has a <b>sprintf</b> which has provide the same functionality of php sprintf.

```php
echo $this->translator->sprintf('There are %d monkeys in the %s.', 5, 'tree');

// Gives There are *5* monkeys in the *tree*.
```

### Setting Locale

Translator class construct method set default locale of user using these methods: 

1 - It sets locale using Http GET : 

```php
http://example.com/home?locale=es_ES
```

Translator file config should be like this

```php
<?php
// Http Settings
'query_string' => array('enabled' => true, 'key' => 'locale'),
```

2 - It sets locale using http URI Segment :

```php
http://example.com/home/en
```

Translator file config should be like this

```php
<?php
'query_string' => array('enabled' => false, 'key' => 'locale'),
'uri_segment'  => array('enabled' => true, 'key' => 'locale', 'segment_number' => 1),
```

3 - It sets locale using http COOKIE if URI Segment and Http GET not provided : 

Translator file config should be like this

```php
<?php
// Cookies
'cookie_prefix' => 'locale_',
'cookie_domain' => '',         // Set to .your-domain.com for site-wide cookies
'cookie_path'   => '',         // Typically will be a forward slash
'cookie_expire' => (365 * 24 * 60 * 60), // 365 day; //  @see  Cookie expire time.   http://us.php.net/strtotime
'cookie_secure' => false,      // Cookies will only be set if a secure HTTPS connection exists.
```

4 - It sets using <b>locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])</b> function if language code not provided with any of above the methods : 

Translator file config should be like this

```php
<?php
// Php Intl Settings          
'locale_set_default' => true,
```

**Note:** locale_accept_from_http() function required php <b>intl</b> extension.

### Locale Code Reference

See Detailed Iso Language Codes Reference http://www.microsoft.com/resources/msdn/goglobal/default.mspx