<?php

/*
|--------------------------------------------------------------------------
| Logger File Configuration
|--------------------------------------------------------------------------
| Prototype: 
|
|   $logger_file['key'] = value;
|
*/
$logger_file = array(
    'batch'     => true,                   // batch process should be enabled for best performance
    'path'      => 'data/logs/app.log',
    'path_cli'  => 'data/logs/cli/app.log',    
    'path_task' => 'data/logs/tasks/app.log',
);
/*
|--------------------------------------------------------------------------
| Extend to format function
|--------------------------------------------------------------------------
| Formatter function must return to @array.
| 
*/
$logger_file['extend']['format'] = function ($record) {
    if (sizeof($record['context']) == 0) {
        $record['context'] = '';
    } else {
        $record['context'] = preg_replace('/[\r\n]+/', '', var_export($record['context'], true));
    }
    $record['datetime'] = date('Y-m-d H:i:s');  // set your datetime format
    return $record;
};
/*
|--------------------------------------------------------------------------
| Extend to write function
|--------------------------------------------------------------------------
| Write function must return to @boolean.
| 
*/
$logger_file['extend']['write'] = function ($file, $data) {
    if ( ! $fop = fopen($file, 'ab')) {
        return false;
    }
    flock($fop, LOCK_EX);
    fwrite($fop, $data);
    flock($fop, LOCK_UN);
    fclose($fop);
    if ( ! defined('STDIN')) {   // Do not do (chmod) in CLI mode, it cause write errors
        chmod($file, 0666);
    }
    return true;
};

/* End of file logger_file.php */
/* Location: .app/config/debug/logger_file.php */