## URI Class

The URI Class provides functions that help you retrieve information from your URI strings. If you use URI routing, you can also retrieve information about the re-routed segments.

**Note:** This class is initialized automatically by the system so there is no need to do it manually.

**Note:** This class is a <kbd>component</kbd> defined in your package.json. You can <kbd>replace components</kbd> with third-party packages.

#### $this->uri->baseUrl();

This function retrieves the URL to your site without "index" value you've specified in the config file.

```php
echo $this->uri->baseUrl();  // output "/" ( forward slash )
```

#### $this->uri->siteUrl($uri = '', $suffix = true);

This function retrieves the URL to your site, along with the "index" value you've specified in the config file.If you set before url suffix (like .html) in config.php using second parameter <b>$suffix = false</b> you can switch off suffix for current site url.

The index.php file (or whatever you have set as your site index_page in your config file) will be added to the URL, as will any URI segments you pass to the function. You are encouraged to use this function any time you need to generate a local URL so that your pages become more portable in the event your URL changes. Segments can be optionally passed to the function as a string or an array. Here is a string example:

```php
echo $this->uri->siteUrl("news/local/start/123");
```

The above example would return something like: /index.php/news/local/123.

Here is an example of segments passed as an array:

```php
$segments = array('news', 'local',  'start', '123');

echo $this->uri->siteUrl($segments);
```

#### $this->uri->currentUrl();

Returns the full URL (including segments) of the page being currently viewed.

#### $this->uri->segment(n)

Permits you to retrieve a specific segment. Where n is the segment number you wish to retrieve. Segments are numbered from left to right. For example, if your full URL is this:

```php
http://example.com/index.php/news/local/metro/crime_is_up
```

The segment numbers would be this:

* (0) news
* (1) local
* (2) metro
* (3) crime_is_up

By default the function returns false (boolean) if the segment does not exist. There is an optional second parameter that permits you to set your own default value if the segment is missing. For example, this would tell the function to return the number zero in the event of failure:

```php
$product_id = $this->uri->segment(3, 0);
```

It helps avoid having to write code like this:

```php
if ($this->uri->segment(3) === false)
{
    $product_id = 0;
}
else
{
    $product_id = $this->uri->segment(3);
}
```

#### $this->uri->routedSegment(n)

This function is identical to the previous one, except that it lets you retrieve a specific segment from your re-routed URI in the event you are using Obullo's URI Routing <kbd>/docs/advanced/uri-routing</kbd> feature.

#### $this->uri->slashSegment(n)

This function is almost identical to <kbd>$this->uri->segment()</kbd>, except it adds a trailing and/or leading slash based on the second parameter. If the parameter is not used, a trailing slash added. Examples:

```php
$this->uri->slashSegment(3);
$this->uri->slashSegment(3, 'leading');
$this->uri->slashSegment(3, 'both');
```

Returns:

* segment/
* /segment
* /segment/

#### $this->uri->slashRoutedSegment(n)

This function is identical to the previous one, except that it lets you add slashes a specific segment from your re-routed URI in the event you are using Obullo's URI Routing <kbd>/docs/advanced/uri-routing</kbd> feature.

#### $this->uri->uriToAssoc(n)

This function lets you turn URI segments into an associative array of key/value pairs. Consider this URI:

```php
index.php/user/search/name/joe/location/DE/gender/male
```

Using this function you can turn the URI into an associative array with this prototype:

```php
[array]
(
    'name'     => 'joe'
    'location' => 'DE'
    'gender'   => 'male'
)
```

The first parameter of the function lets you set an offset. By default it is set to <kbd>3</kbd> since your URI will normally contain a controller/function in the first and second segments. Example:

```php
$array = $this->uri->uriToAssoc(3);
echo $array['name']; 
```

The second parameter lets you set default key names, so that the array returned by the function will always contain expected indexes, even if missing from the URI. Example:

```php
$default = array('name', 'gender', 'location', 'type', 'sort');

$array = $this->uri->uriToAssoc(3, $default);
```

If the URI does not contain a value in your default, an array index will be set to that name, with the value of false.

Lastly, if a corresponding value is not found for a given key (if there is an odd number of URI segments) the value will be set to false (boolean).

#### $this->uri->routedUriToAssoc(n)

This function is identical to the previous one, except that it creates an associative array using the re-routed URI in the event you are using Obullo's URI Routing <kbd>/docs/advanced/uri-routing</kbd> feature.

#### $this->uri->assocToUri()

Takes an associative array as input and generates a URI string from it. The array keys will be included in the string. Example:

```php
$array = array('product' => 'shoes', 'size' => 'large', 'color' => 'red');

$str = $this->uri->assocToUri($array);

// Produces: product/shoes/size/large/color/red
```

#### $this->uri->uriString()

Returns a string with the complete URI. For example, if this is your full URL:

```php
http://example.com/index.php/news/local/345
```

The function would return this:

```php
/news/local/345
```

#### $this->uri->routedUriString(n)

This function is identical to the previous one, except that it returns the re-routed URI in the event you are using Obullo's URI Routing <kbd>/docs/advanced/uri-routing</kbd> feature.

#### $this->uri->totalSegments()

Returns the total number of segments.

#### $this->uri->totalRoutedSegments()

This function is identical to the previous one, except that it returns the total number of segments in your re-routed URI in the event you are using URI Routing <kbd>/docs/advanced/uri-routing</kbd> feature.

#### $this->uri->segmentArray()

Returns an array containing the URI segments. For example:

```php
$segs = $this->uri->segmentArray();

foreach ($segs as $segment)
{
    echo $segment;
    echo '<br />';
}
```

#### $this->uri->routedSegmentArray()

This function is identical to the previous one, except that it returns the array of segments in your re-routed URI in the event you are using Obullo's URI Routing <kbd>/docs/advanced/uri-routing</kbd> feature.

#### $this->uri->extension()

You can use uri extensions when you use ajax, xml, rss, json.. requests, you can dynamically change the application behaviours using uri extensions. Also this functionality will help you to create friendly urls.

```php
example.com/module/class/post.json 
```

You can define allowed extensions from your <kbd>app/config/config.php</kbd> file, default allowed URI extensions listed below.

* php
* html
* json
* xml
* raw
* rss
* ajax

Using URI Class $this->uri->extension(); function you can grab the called URI extension. 

```php
switch($this->uri->extension())
{
    case 'json':
        echo json_encode($data);
    break;
    
    case 'html':
        echo $data;
    break;
}
```

#### $this->uri->protocol()

Get the current protocol, function returns any protocol listed below.

* REQUEST_URI
* QUERY_STRING
* PATH_INFO

#### $this->uri->requestUri($urlencode = false)

Returns the request uri like native $_SERVER['REQUEST_URI'] variable.

```php
echo $this->uri->requestUri();  //  /search/index?var=val1&query=val2 

echo $this->uri->requestUri(true);  //  %2Fsearch%2Findex%3Fvar%3Dval1%26query%3Dval2 
```