## URL Helper

The URL Helper file contains functions that assist in working with URLs.

The following functions are available:

#### Url::anchor($uri = '', $title = '', $attributes = '', $suffix = true)

Creates a standard HTML anchor link based on your local site URL:

```php
<a href="http://example.com">Click Here</a>
```

The tag has four optional parameters:

```php
Url::anchor(uri segments, text, attributes, $suffix)
```

The first parameter can contain any segments you wish to append to the URL. As with the url\site() function above, segments can be a string or an array.

**Note:** If you are building links that are internal to your application do not include the base URL (http://...). This will be added automatically from the information specified in your config file. Include only the URI segments you wish.

The second segment is the text you would like the link to say. If you leave it blank, the URL will be used.

The third parameter can contain a list of attributes you would like to add to the link. The attributes can be a simple string or an associative array.

If you set before url suffix (like .html) in config.php using fourth parameter **$suffix = false** you can switch off suffix for current site url.

Here are some examples:

```php
echo Url::anchor('news/local/123', 'title="My News"');
```

Would produce: <a href="news/local/123" title="My News">My News</a>

```php
echo Url::anchor('news/local/123', 'My News', array('title' => 'The best news!'));
```

Would produce: <a href="news/local/123" title="The best news!">My News</a>

Url anchor function support **'#'** characters. You can use it like this ..

```php
echo Url::anchor('news/local/123#sharp_url');
```

#### Url::anchorPopup()

Nearly identical to the anchor() function except that it opens the URL in a new window. You can specify JavaScript window attributes in the third parameter to control how the window is opened. If the third parameter is not set it will simply open a new window with your own browser settings. Here is an example with attributes:

```php
$atts = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '0',
              'screeny'    => '0'
            );

echo Url::anchorPopup('news/local/index/123', 'Click Me!', $atts);
```

**Note:** The above attributes are the function defaults so you only need to set the ones that are different from what you need. If you want the function to use all of its defaults simply pass an empty array in the third parameter:

```php
echo Url::anchorPopup('news/local/index/123', 'Click Me!', array());
```

#### Url::title()

Takes a string as input and creates a human-friendly URL string. This is useful if, for example, you have a blog in which you'd like to use the title of your entries in the URL. Example:

```php
echo Url::title("What's wrong with CSS?", $title);

// Produces: Whats-wrong-with-CSS
```

The second parameter determines the word delimiter. By default dashes are used. Options are: dash, or underscore:

```php
echo Url::title("What's wrong with CSS?", 'underscore');

// Produces: Whats_wrong_with_CSS
```

The third parameter determines whether or not lowercase characters are forced. By default they are not. Options are boolean true/false:

```php
echo Url::title("What's wrong with CSS?", 'underscore', true);

// Produces: whats_wrong_with_css
```

#### Url::prep()

This function will add http:// in the event it is missing from a URL. Pass the URL string to the function like this:

```php
$url = Url::prep("example.com");
```

#### Url::redirect($uri = '', $method = 'location', $http_response_code = '302', $suffix = true)

Does a "header redirect" to the local URI specified. Just like other functions in this helper, this one is designed to redirect to a local URL within your site. You will not specify the full site URL, but rather simply the URI segments to the controller you want to direct to. The function will build the URL based on your config file values.

The optional second parameter allows you to choose the "location" method (default) or the "refresh" method. Location is faster, but on Windows servers it can sometimes be a problem. The optional third parameter allows you to send a specific HTTP Response Code - this could be used for example to create 301 redirects for search engine purposes. The default Response Code is 302. The third parameter is only available with 'location' redirects, and not 'refresh'. Examples:

```php
if ($loggedin == false)
{
	Url::redirect('login/form', 'refresh[0]');
}

// with 301 redirect
Url::redirect('news/article/13', 'location', 301);
```

If you set before url suffix (like .html) in config.php using fourth parameter $suffix = false you can switch off suffix for current site url.

**Note:** In order for this function to work it must be used before anything is outputted to the browser since it utilizes server headers.

**Note:** For very fine grained control over headers, you should use the Output Class <kbd>/docs/packages/output</kbd>'s setHeader() function.

If you use refresh parameter you can set refresh time.

```php
Url::redirect('/payments/response_status', 'refresh[4]');  // output  header("Refresh:4;url=/payments/response_status");
```