<?php
/*
|--------------------------------------------------------------------------
| Application Constants.
|--------------------------------------------------------------------------
| This file specifies which APP constants should be loaded by default.
|
 */
if ( ! defined('ROOT')) {
    include 'constants';
}
/*
|--------------------------------------------------------------------------
| Choose Your Environment
|--------------------------------------------------------------------------
| This can be set to anything, but default usage is:
|
|     o debug - Debug Mode  ( Quick Debugging, show all php errors )
|     o test  - Testing     ( Test mode, behaviours like Live )
|     o live  - Production  ( Production mode, all errors disabled )
|
*/
define('ENV', 'debug');
/*
|--------------------------------------------------------------------------
| Load Autoloader
|--------------------------------------------------------------------------
*/
require OBULLO .'Obullo'. DS .'Autoloader'. EXT;
/*
|--------------------------------------------------------------------------
| Load Your Application & Services
|--------------------------------------------------------------------------
*/
require ROOT .'env'. DS . ENV. DS .'app'. EXT;
/*
|--------------------------------------------------------------------------
| Load Framework
|--------------------------------------------------------------------------
*/
require OBULLO .'Obullo'. DS .'Obullo'. EXT;