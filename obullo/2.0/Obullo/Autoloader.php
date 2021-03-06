<?php

/**
 * Obullo
 * 
 * @category  Autoloader
 * @package   Obullo
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://www.php-fig.org/psr/psr-0/
 */

/**
 * PSR-0 Autoloader
 * 
 * @param string $realname classname 
 *
 * @see http://www.php-fig.org/psr/psr-0/
 * 
 * @return void
 */
function Obullo_autoloader($realname)
{
    if (class_exists($realname, false)) {  // https://github.com/facebook/hiphop-php/issues/947
        return;
    }
    $className = ltrim($realname, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . EXT;

    if (strpos($fileName, 'Obullo') === 0) {     // Check is it Obullo Package ?
        include_once OBULLO .substr($fileName, 7);
        // echo $filename.'<br>';
        return;
    }
    include_once CLASSES .$fileName; // Otherwise load it from user directory
}
spl_autoload_register('Obullo_autoloader', true);

/**
 * Register Error Handler
 * If framework debug enabled
 * register error & exception handlers.
 */
if ($c['config']['debug']) {
    Obullo\Error\Debug::enable(E_ALL | E_STRICT);
}

// END Autoloader.php File
/* End of file Autoloader.php

/* Location: .Obullo/Obullo/Autoloader.php */