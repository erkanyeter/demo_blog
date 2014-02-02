## Cache Class

------

Framework features wrappers around some of the most popular forms of fast and dynamic caching. All but file-based caching require specific server requirements, and a Fatal Exception will be thrown if server requirements are not met.

### Initializing the Class

------

```php
new Cache;
$this->cache->method();
```

Once loaded, the Cache object will be available using: <dfn>$this->cache->method()</dfn>

The following functions are available:

### Creating a Cache Data

The easiest way to create cache with default settings

```php
new Cache();

$id   = "test";
$data = "cache test";
$ttl  = 20; // default 60 seconds

$this->cache->save($id,$data,$ttl);
```

### Getting the cached value.

```php
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

```php
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

```php
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

```php
'driver'  => 'apc'
```

#### File Cache

If you send the config to the class yourself, you need to be sure that the cache_path variable is defined correctly.

Example

```php
$config = array(
				  'driver'	   => 'file',
				  'cache_path' => /data/temp/cache/
				  );

new Cache($config);
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

```php
'driver'  => 'memcached'
```

#### Connect configuration for Memcache or Memcached

If you want to establish a connection without the default settings in the config

```php
$connection = array(
					  'driver'  => 'memcache',
					  'servers'	=> array(
				  						 'hostname' => '127.0.0.1',
				  						 'port'     => '11211',
				  						 'weight'   => '1'
				  						),
					 );
new Cache($connection);


```php
Under the array servers, you can create multi connection creating nested arrays. 

```php
$connection = array(
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

#### The methods below ara available for all drivers.

---

### $this->cache->set(string $key, string $data, int $expiration);

```php
$this->cache->set('test','cache test', 20); // default 60 seconds
```

### $this->cache->get(string $key);

You can get the saved values using this function.

```php
$this->cache->get($key);
```

### $this->cache->getMetaData(string $key);

You can reach the meta information of data with this function.

```php
$this->cache->getMetaData($key);
```
### $this->cache->delete(string $key);

Deletes the data of the specified key.

```php
$this->cache->delete($key);
```

### $this->cache->clean();

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

### Complete Example with Manuel Connection

```php
$connection = array(
				  'driver'	=> 'memcached',
				  'servers'	=> array(
				  					 'hostname' => '127.0.0.1',
				  					 'port'     => '11211',
				  					 'weight'   => '1'
				  					 ),
				 );
new Cache($connection);

$this->cache->set('test','cache test', 20); // default 60 seconds
$this->cache->get('test');
$this->cache->delete('test'); // delete selected key

$this->cache->clean(); // destroy all keys
```

### Function Reference

---

#### $this->cache->get($key);

Get cache data providing by your key.

#### $this->cache->set($key, $data, $expiration_time);

Saves a cache data usign your key.

#### $this->cache->delete($key);

Deletes the selected key.

#### $this->cache->replace($key, $value, $expiration_time);

Replaces the values and expiration time of the given key.

#### $this->cache->clean();

Clears all of the data.

#### $this->cache->getMetaData($key);

Gets the meta information of data of the chosen key.

#### $this->cache->getAllKeys();

Gets the all keys, however, only suitable with memcached.