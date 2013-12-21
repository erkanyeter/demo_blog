## Cache Class

The following functions are available:

### Creating Cache

Default ayarlarla en basit haliyle cache oluşturmak.
```
new Cache();
$id   = "test";
$data = "cache test";
$ttl  = 20; // default 60 seconds
$this->cache->save($id,$data,$ttl);
```
Cache oluşturulan veriyi çekmek.
```
$this->cache->get('test');
```
Default bağlantı ayarlarını config dosyasından ayarlayabilirsiniz.

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

Multi bağlantı için <kbd>servers</kbd> array içine yeni bağlantıyı array olarak ekleyebilirsiniz.

Example
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

Config de ki driver tanımlamasını <kbd>apc</kbd> olarak değiştirmeniz gerekmektedir.
```
'driver'  => 'apc'
```

#### File Cache
Classa configi kendiniz gönderiyorsanız, cache_path değişkenini doğru bir şekilde tanımladığından emin olmalısınız.

Example
```
$myConfig = array(
				  'driver'	   => 'file',
				  'cache_path' => DATA .'temp'. DS .'cache'. DS // Just cache file .data/temp/cache
				  );

new Cache($myConfig);
```
Cache klasörüne yazma izni <kbd>chmod 777</kbd> vermeniz gerekmektedir.

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
Config dosyasında ki geçerli bağlantı ayarlarını kullanmadan bağlantı kurmak isterseniz

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
Servers arrayı altında iç içe dizi oluştururak multi bağlantı yapabilirsiniz.
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

####Aşağıda ki methodlar tüm driverlar için geçerlidir.
---

### Set function

```php
$this->cache->set('test','cache test', 20); // default 60 seconds
```

### Get function
Save yapılan değerleri bu methodla çekebilirsiniz.

```php
$this->cache->get($id);
```

### Get Meta Data
Datanın meta bilgilerine bu fonksiyonla ulaşabilirsiniz.

```php
$this->cache->getMetaData($id);
```
### Delete

Belirtilen id'ye ait veriyi siler.

```php
$this->cache->delete($key);
```

### Clean function
Hafızayı tamamen boşaltmak için kullanacağınız fonksiyondur.

```php
$this->cache->clean();
```
### Complete Example Config
Default ayarlarımızla <kbd>memcached</kbd> cache kullanarak bir örnek oluşturuyoruz.
```php
new Cache();
$id 	= "test";
$data 	= "cache test";
$ttl  	= 20; // default 60 seconds

$this->cache->save($id,$data,$ttl);
$this->cache->get($id);
$this->cache->delete($id);
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

Get data

#### $this->cache->set($key, $data, $expiration_time);

Save data

#### $this->cache->delete($key);

Deletes the selected ID.

#### $this->cache->replace($key, $value, $expiration_time);


#### $this->cache->clean();

Clears all of the data.

#### $this->cache->getMetaData($key);

Seçilen key ait meta data ları çeker. 

#### $this->cache->getAllKeys();

Tüm key leri getirir sadece memcached driver ile uyumludur


