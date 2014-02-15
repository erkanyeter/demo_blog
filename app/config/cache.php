<?php

/*
|--------------------------------------------------------------------------
| Cache Package Configuration
|--------------------------------------------------------------------------
| Prototype: 
|
|   $cache['key'] = value;
| 
*/

$cache = array(
			   'driver'  => 'redis',
			   'servers' => array(
								  'hostname' => '127.0.0.1',
								  'port'     => '6379',
							   // 'timeout'	 => '2.5' 		// 2.5 sec timeout, just for redis cache
								  'weight'   => '1'			// The weight parameter effects the consistent hashing 
								  							// used to determine which server to read/write keys from.
								  ),
			   
				'cache_path' =>  '/data/temp/cache/',  // Just cache file .data/temp/cache
			   );


/* End of file cache.php */
/* Location: .app/config/cache.php */