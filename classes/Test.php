<?php 

Class Test {

	public function __construct() 
	{
		global $c;
        
        echo $c['Url']->anchor('test', 'Test');
	}

}
