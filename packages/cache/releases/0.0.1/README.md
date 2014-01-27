## Cache Class

The following functions are available:

### Creating Cache

The easiest way to create cache with default settings
```
new Cache();
$id   = "test";
$data = "cache test";
$ttl  = 20; // default 60 seconds
$this->cache->save($id,$data,$ttl);
```
Getting the cached value.
```
$this->cache->get('test');
```
Connection settings can be configured within the config file.

##### Directory of Config File

```php
- app
	- config
		cache.php
```
Example config file
```
$cache = array(
			   'driver'     => 'memcache',
			   'servers'    => array(
								     'hostname' => '127.0.0.1',
									 'port'     => '11211',
									 'weight'   => '1'
									 ),
				'cache_path' => DATA .'temp'. DS .'cache'. DS // Just cache file .data/temp/cache
			   );
```

For multi-connection, a new connection as a new array(inside the <kbd>servers</kbd> array)  can be included to the <kbd>servers</kbd> array  

Example:
```
$cache = array(
			   'driver'	 => 'memcache',
			   'servers' => array(array(
                                        'hostname' => 'localhost',
                                        'port'     => 11211,
                                        'weight'   => 10
                                        ),
                                   array(
                                         'hostname' => '127.0.0.1',
                                         'port'     => 11211,
                                         'weight'   => 20
                                         )),	 
				'cache_path' => DATA .'temp'. DS .'cache'. DS // Just cache file .data/temp/cache
			   );
```

### Drivers

#### Alternative PHP Cache Apc

For more information on APC, please see [http://php.net/apc](http://php.net/apc)

The driver definition in the config file needs to be replaced with <kbd>apc</kbd>.
```
'driver'  => 'apc'
```

#### File Cache
If you send the config to the class yourself, you need to be sure that the cache_path variable is defined correctly.

Example
```
$myConfig = array(
				  'driver'	   => 'file',
				  'cache_path' => DATA .'temp'. DS .'cache'. DS // Just cache file .data/temp/cache
				  );

new Cache($myConfig);
```
The cache folder should be given the write permission <kbd>chmod 777</kbd>. 

##### Temp Cache Directory
```php
+ app
+ assets
- data
	- temp
		cache
```
#### Memcache - Memcached
```
'driver'  => 'memcached'
```

#### Connect configuration for Memcache or Memcached
If you want to establish a connection without the default settings in the config

```php
$myConnection = array(
					  'driver'  => 'memcache',
					  'servers'	=> array(
				  						 'hostname' => '127.0.0.1',
				  						 'port'     => '11211',
				  						 'weight'   => '1'
				  						),
					 );
new Cache($myConnection);
```
Under the array servers, you can create multi connection creating nested arrays. 
```php
$myConnection = array(
					  'driver' => 'memcached',
					  'servers' => array(array(
                                        	   'hostname' => 'localhost',
                                        	   'port'     => 11211,
                                        	   'weight'   => 10
                                        	  ),
                                       	 array(
                                       	 	   'hostname' => '127.0.0.1',
                                       	 	   'port'     => 11211,
                                       	 	   'weight'   => 20
                                       	 	   )),
					 );
```

####The methods below ara available for all drivers.
---

### Set function

```php
$this->cache->set('test','cache test', 20); // default 60 seconds
```

### Get function
You can get the saved values using this function.

```php
$this->cache->get($key);
```

### Get Meta Data
You can reach the meta information of data with this function.

```php
$this->cache->getMetaData($key);
```
### Delete

Deletes the data of the specified key.

```php
$this->cache->delete($key);
```

### Clean function
Cleans the memory completely.

```php
$this->cache->clean();
```
### Complete Example Config
Using <kbd>memcached</kbd> cache, we make a sample with the default settings:
```php
new Cache();
$id 	= "test";
$data 	= "cache test";
$ttl  	= 20; // default 60 seconds

$this->cache->save($key,$data,$ttl);
$this->cache->get($key);
$this->cache->delete($key);
$this->cache->clean();
```
### Complete Example Manuel Connection

```php
$myConfig = array(
				  'driver'	=> 'memcached',
				  'servers'	=> array(
				  					 'hostname' => '127.0.0.1',
				  					 'port'     => '11211',
				  					 'weight'   => '1'
				  					 ),
				 );
new Cache($myConfig);

$this->cache->set('test','cache test', 20); // default 60 seconds
$this->cache->get('test');
$this->cache->delete('test'); // delete selected key

$this->cache->clean(); // destroy all keys
```

### Function Reference

---

#### $this->cache->get($key);

Gets data

#### $this->cache->set($key, $data, $expiration_time);

Saves data

#### $this->cache->delete($key);

Deletes the selected key.

#### $this->cache->replace($key, $value, $expiration_time);

Replaces the values and expiration time of the given key

#### $this->cache->clean();

Clears all of the data.

#### $this->cache->getMetaData($key);

Gets the meta information of data of the chosen key.

#### $this->cache->getAllKeys();

Gets the all keys, however, only suitable with memcached.


