	'exceptions' => function($e, $type){
        $exception = new Exceptions;     
        return $exception->write($e, $type);
	}