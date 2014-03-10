<?php

/*
|--------------------------------------------------------------------------
| Logger Dependency Container
|--------------------------------------------------------------------------
| Configure your logger service
|
*/
$logger['instance'] = function ($params) { 
    return new Logger($params); 
};

$logger['debug'] = function ($message,$context) use ($logger) {
    $log = $logger['instance']();
    return $log->debug($message, $context);
};

$logger['error'] = function ($message,$context) use ($logger) {
    $log = $logger['instance']();
    return $log->error($message, $context);
};

$logger['info'] = function ($message,$context) use ($logger) {
    $log = $logger['instance']();
    return $log->info($message, $context);
};

$logger['alert'] = function ($message,$context) use ($logger) {
    $log = $logger['instance']();
    return $log->alert($message, $context);
};


/* End of file mailer.php */
/* Location: .app/config/dependencies/mailer.php */