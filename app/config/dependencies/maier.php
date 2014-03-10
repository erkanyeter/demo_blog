<?php

/*
|--------------------------------------------------------------------------
| Mailer Dependency Container ( Mailer service olmalı !!!!!!!!!!! )
|--------------------------------------------------------------------------
| Configure your mailer service
|
*/
$mailer['instance'] = function($params){ 
	return new Emailer($params); 
};

$mailer['extend']['to']   = function(){};
$mailer['extend']['send']  = function(){};
$mailer['extend']['bcc']  = function(){};


/* End of file mailer.php */
/* Location: .app/config/dependencies/mailer.php */