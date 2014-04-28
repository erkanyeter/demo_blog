## Config Class

------

The Config class provides a means to retrieve configuration preferences. These preferences can come from the default config file <kbd>app/config/env/local/config.php</kbd> or from your own custom config files.

### Initializing a Task Class

------

```php
$c['config'];
$this->config->method();
```

### Accessing config data

------

```php
<?php
echo $this->config['locale']['default_translation'];  // gives  "en_US"
```

### Creating a Config File

------

By default, Framework has one primary config file, located at <kbd>app/config/env/local/config.php</kbd>. 

If you open the file using your text editor you'll see that config items are stored in an array called <var>$config</var>.

You can add your own config items to this file, or if you prefer to keep your configuration items separated (assuming you even need config items), simply create your own file and save it in <dfn>config</dfn> folder.

**Note:** If you do create your own config files use the same format as the primary one, storing your items in an array called $config. 

Obullo will intelligently manage these files so there will be no conflict even though the array has the same name ( assuming an array index is not named the same as another ).

### Loading a Config File

------

**Note:** Framework automatically loads the primary config file <kbd>app/config/env/local/config.php</kbd>, so you will only need to load a config file if you have created your own.

To load one of your custom config files you will use the following function within the <samp>controller</samp> that needs it:

```php
<?php
$this->config->load('filename');
```

### Getting Config Items

------

To retrieve an item from your config file, use the following function:

```php
$this->config['itemname'];
```

Where <var>itemname</var> is the <dfn>$config<dfn> array index you want to retrieve. For example, to fetch your language choice you'll do this:


### Loading From Environment folder

```php
<?php
$this->config->load('filename', true);
```
```php
$lang = $this->config['locale']['default_translation'];
```

### Setting a Config Item

------

If you would like to dynamically set a config item or change an existing one, you can do using:

```php
$this->config['item'] = 'value';
```

Where <var>item</var> is the $config array index you want to change, and <var>value</var> is its value.