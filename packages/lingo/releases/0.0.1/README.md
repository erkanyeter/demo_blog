## Lingo Class

The Lingo Class provides functions to retrieve language files and lines of text for purposes of internationalization.

In your app folder you'll find one called lingo containing sets of language files. You can create your own language files as needed in order to display error and other messages in other languages.

Language files are typically stored in your <kbd>app/lingo</kbd> directory.

**Note:** Each language should be stored in its own folder. For example, the English files are located at: <kbd>app/lingo/english</kbd>

**Note:** This class is a component defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

### Creating Language Files

------

Within the file you will assign each line of text to an array called <var>$lang</var> with this prototype:

```php
$lang['language_key'] = "The actual message to be shown";
```

**Note:** It's a good practice to use actual message as key for all messages in a given file to avoid collisions with similarly named items in other files. 

```php
$lang['You must submit an email address'] = "You must submit an email address";
$lang['You must submit a URL']  = "You must submit a URL";
$lang['You must submit a username'] = "You must submit a username";
```

```php
$this->lingo->load('language', $folder= '');  // load lingo file
```

Where <samp>filename</samp> is the name of the file you wish to load (without the file extension), and language is the language set containing it (ie, english). If the second parameter is missing, the default language set in your <kbd>app/config/config.php</kbd> file will be used.

### Loading a Lingo File

------

If you want load language files from your <b>app</b> folder create your language files to there ..

```php
-  app
    + cache
    + config
    + errors
    - lingo
        - english
            blog.php
            welcome.php
            contact.php 
```

This function load a language file from your <kbd>app/lingo</kbd> folder.

```php
new Cookie;

$this->lingo->load('welcome', $this->cookie->get('lang'));

lingo('Welcome to the our site !');
```

For Example :

```php
$this->lingo->load('calendar','spanish');
```

### Loading the Obullo Language

Some of the packages use Obullo language file which is located in your <kbd>app/lingo</kbd> folder. You can change the default language. For example, if you set your default language to <b>spanish</b> ( look at <kbd>app/config/config.php</kbd> ) 
core packages will load obullo.php from <kbd>app/lingo/spanish</kbd> folder.

------

```php
-  app
    +cache
    +config
    +errors
    -lingo
        -english
            obullo.php
            welcome.php
            ...
        -spanish
            obullo.php
            welcome.php
            ...
    +logs
```

This function load the <b>welcome</b> language file from your <kbd>app/lingo/spanish</kbd> folder.

```php
$this->lingo->load('welcome', 'spanish'); 
```

```php
$this->lingo->load('welcome'); // default english if you not provide second parameter 
```

### Fetching a Line of Text

------

Once your desired language file is loaded you can access any line of text using this function:

```php
lingo('language_key');
```

### Using Sprintf()

<b>lingo()</b> function automatically supports <b>sprintf</b> functionality so you <b>don't</b> need to use sprintf.

```php
echo lingo('There are %d monkeys in the %s.',5,'tree');

// There are 5 monkeys in the tree.

```

Where <samp>language_key</samp> is the array key corresponding to the line you wish to show.

**Note:** This function simply returns the line. It does not echo it for you.

### Auto-loading Languages

------

If you find that you need a globally particular language throughout your application, you can tell Framework autoloader functions ( see the /docs/advanced/auto-loading ) it during system initialization.

```php
$autoload['lingo'] = array('languagefile');
```
