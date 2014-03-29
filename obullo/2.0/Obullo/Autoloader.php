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
// ------------------------------------------------------------------------

/**
 * PSR-0 Autoloader
 * 
 * @param string $realname classname 
 * 
 * @return void
 */
function Obullo_autoloader($realname)
{
    if (class_exists($realname, false)) {  // https://github.com/facebook/hiphop-php/issues/947
        return;
    }
    global $version;

    $className = ltrim($realname, '\\');  // http://www.php-fig.org/psr/psr-0/
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . EXT;

    if (strpos($fileName, 'Obullo') === 0) {     // Check is it a Obullo Class ?
        include_once OBULLO .$version. DS .substr($fileName, 7);
        return;
    }
    include_once CLASSES .$fileName; // Otherwise load it from user directory
}
spl_autoload_register('Obullo_autoloader', true);


// END Autoloader.php File
/* End of file Autoloader.php

/* Location: .Obullo/Obullo/Autoloader.php */