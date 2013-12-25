<?php

/**
 * Schema Mysql Driver for Model Class
 *
 * @package       packages 
 * @subpackage    schema_mysql
 * @category      models
 * @link            
 */

Class Schema_Mysql {

	public $db;		   	     // Database object
	public $tablename; 		 // Valid tablename
	public $modelName; 		 // Model not may not be same name some times
	public $schemaObject;	 // Schema Object

	/**
	 * Construct Schema Driver Paramaters
	 * 
	 * @param object $schemaObject
	 * @param object $dbObject
	 */
	public function __construct(Schema $schemaObject)
	{
		$this->schemaObject = $schemaObject; // Store schema object

		$this->tablename = $schemaObject->getTableName();
		$this->modelName = $schemaObject->getModelName();
		$this->db 		 = $schemaObject->getDbObject();
	}

	// --------------------------------------------------------------------

	/**
	 * Build the Schema File content
	 * using your database table.
	 * 
	 * @return string schema file string
	 */
	public function read()
	{
		if($this->tableExists() == 1) // If Already exists read from sql ?
		{		
			$tableReader   = new Schema_Mysql\Src\Schema_Sql_Reader($this->db); // Read column information from table
	        $schemaContent = $tableReader->readSQL($this->tablename);

	        if($schemaContent == false)
	        {
	        	return false;
	        }

	        return $schemaContent;
		}
		
		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Read schemaArray and build Mysql
	 * Query Output
	 * 
	 * @return string sql output
	 */
	public function create()
	{
		$tableCreator = new Schema_Mysql\Src\Schema_Sql_Creator();

        return $tableCreator->createSQL($this->tablename);
	}

	// --------------------------------------------------------------------

	/**
	 * Synchronize schema model
	 * and database table
	 * 
	 * @return string sync results
	 */
	public function sync()
	{
		$schemaContent = $this->read();

		if($schemaContent != false)
		{
			$sync_mysql = new Schema_Mysql\Src\Schema_Sync($schemaContent, $this->schemaObject);
			$sync_mysql->run(); 

			if($sync_mysql->collisionExists())
			{	
				echo $sync_mysql->output(); // Display sync table to developer
				exit;  				  		// die current process
			}
		}
	}
 
	// --------------------------------------------------------------------
	
	/**
	 * Check table exists
	 * 
	 * @return int
	 */
	public function tableExists()
	{
        $this->db->query("SHOW TABLES LIKE '".$this->tablename."'");

        if($this->db->count() > 0)
        {
        	return true;
        }

       	return false;
	}
}

// END Schema_Mysql class

/* End of file schema_mysql.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/schema_mysql.php */