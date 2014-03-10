<?php

/*
|--------------------------------------------------------------------------
| Logger Mongo Configuration
|--------------------------------------------------------------------------
| Configure Logger Mongo 
|
| Prototype: 
|
|   $logger_mongo['key'] = value;
|
*/
$logger_mongo = array(
    'batch'      => true,   // multiline process
    'collection' => 'log_mycollection',   // Set your mongo collection 
);
/*
|--------------------------------------------------------------------------
| Extend to format function
|--------------------------------------------------------------------------
| Formatter function must return to @array.
| 
*/
$logger_mongo['extend']['format'] = function ($record) {
    if (count($record['context']) > 0) {     // context
        $record['context'] = json_encode($record['context'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); 
    }
    $record['datetime'] = new MongoDate();   // datetime
    return $record;  // return to array
};
/*
|--------------------------------------------------------------------------
| Extend to batch process
|--------------------------------------------------------------------------
| Multiline logging
| 
*/
$logger_file['extend']['batch_format'] = function ($handler, $records) {
    $formatted = array();
    foreach ($records as $record) {
        $formatted[] = $handler->format($record);
    }
    return $formatted;
};
/*
|--------------------------------------------------------------------------
| Extend to write function
|--------------------------------------------------------------------------
| Write function must return to @boolean.
| 
*/
$logger_mongo['extend']['write'] = function ($record) use ($logger_mongo) {

    $connection = $logger_mongo['connection'];
    $mongo      = $connection();

    $mongo->insert(
        'table', 
        array(
        'datetime' => $record['datetime'], 
        'channel'  => $record['channel'],
        )
    );
};

/* End of file logger_mongo.php */
/* Location: .app/config/debug/logger_mongo.php */