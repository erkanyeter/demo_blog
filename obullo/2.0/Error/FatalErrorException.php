<?php

namespace Obullo\Error;

/**
 * Fatal Error Exception.
 */
class FatalErrorException extends \ErrorException
{
}

        // $shutdownErrors = array(
        //     'ERROR' => 'ERROR', // E_ERROR 
        //     'PARSE ERROR' => 'PARSE ERROR', // E_PARSE
        //     'COMPILE ERROR' => 'COMPILE ERROR', // E_COMPILE_ERROR   
        //     'USER FATAL ERROR' => 'USER FATAL ERROR', // E_USER_ERROR
        // );
        // $shutdownError = false;
        // if (isset($shutdownErrors[$type])) {  // We couldn't use any object for shutdown errors.

        //     $shutdownError = true;
        //     $type = ucwords(strtolower($type));
        //     $code = $e->getCode();

        //     if (defined('STDIN')) {  // If Command Line Request.
        //         echo $type . ': ' . $e->getMessage() . ' File: ' . $c['error']->getSecurePath($e->getFile()) . ' Line: ' . $e->getLine() . "\n";
        //         $cmdType = (defined('TASK')) ? 'Task' : 'Cmd';

        //         if ($c['error']->log_enabled) {
        //             $c['logger']->error('(' . $cmdType . ') ' . $type . ': ' . $e->getMessage() . ' ' . $c['error']->getSecurePath($e->getFile()) . ' ' . $e->getLine());
        //         }
        //         return;
        //     }
        //     include OBULLO . 'Exception' . DS . 'Html' . EXT;

        //     if ($c['logger'] instanceof Obullo\Logger\Logger) {
        //         $c['logger']->error($type . ': ' . $e->getMessage() . ' ' . $c['error']->getSecurePath($e->getFile()) . ' ' . $e->getLine());
        //     }
        // }