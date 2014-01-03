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
     * Build schema string
     * 
     * @param  fieldname $key
     * @param  database types $types
     * @param  string $newType
     * @return string
     */
    public function buildSchemaField($key, $types, $newType = '')
    {
        $fileSchema = getSchema($this->tablename);
        $currentFileSchema = $fileSchema;
        unset($fileSchema['*']);  // Get only fields no settings

        $colprefix = $this->schemaObject->getPrefix();

        if( ! empty($colprefix))  // replace prefix and rewrite no prefix fields into schema
        {
            $key = str_replace($colprefix, '', $key);
        }

        $currentPrefix = $currentFileSchema['*']['colprefix'];
        $newKey = $key;
        if(empty($currentPrefix))
        {
            $newKey = $this->schemaObject->getPrefix().$key;
        }

        $label = (isset($currentFileSchema[$key]['label'])) ? $currentFileSchema[$key]['label'] : $this->schemaObject->_createLabel($key);
        
        $rules = (isset($currentFileSchema[$key]['rules'])) ? $currentFileSchema[$key]['rules'] : '';

        $ruleString = "\n\t'$newKey' => array(";
        $ruleString.= "\n\t\t'label' => '$label',";  // fetch label from current schema

        // --------- RENDER FUNC ----------//
        
        if(isset($currentFileSchema[$key]['func']))
        {
	    	$schemaFile = file_get_contents($this->schemaObject->getPath());
	    	$schemaFile = str_replace('<?php','',$schemaFile);

	    	preg_match("/'$key'(.*?)'func'(\s*)(=>)(\s*)(.*?)\},/s",$schemaFile, $matches);

	        if( isset($matches[5]))
	        {
	            $ruleString.= "\n\t\t'func' => $matches[5]},";  // fetch _func from current schema
	        }
        }

        // --------- RENDER FUNC ----------//

        if(empty($newType))
        {
            if(isset($currentFileSchema[$key]['_enum']))  // if _enum exists convert it to string for array rendering.
            {
                $enumData = '(';
                foreach($currentFileSchema[$key]['_enum'] as $v)
                {
                    $enumData.= '"'.$v.'",';
                }

                $enumData = rtrim($enumData,',');
                $enumData .= ')';
                $types = str_replace('_enum', '_enum'.$enumData, $types);
            }

            if (preg_match('/(_enum)(\(.*?\))/',$types, $match) ) // if type is enum create enum field as an array
            {
                $enumStr  = $match[0];  // _enum("","")
                $enum     = $match[1];  // _enum
                $enumData = $match[2];  // ("","")

                $types = preg_replace('/'.preg_quote($enumStr).'/', '_enum', $types);
                
                $ruleString .= "\n\t\t'_enum' => array(";   // render enum types
                foreach(explode(',', trim(trim($enumData, ')'),'(')) as $v)
                {
                    $ruleString .= "\n\t\t\t".str_replace('"',"'",$v).","; // add new line after that for each comma
                }

                $ruleString .= "\n\t\t),";

                $types = str_replace($enumData, '', $types);
            }

            $ruleString.= "\n\t\t'types' => '".$types."',";
        }
        else 
        {
            $typeStr    = (is_array($types)) ? $newType : $types.'|'.$newType; // new field comes as array data we need to prevent it.
            $ruleString.= "\n\t\t'types' => '".$typeStr."',";
        }

        $ruleString.= "\n\t\t'rules' => '$rules',"; // fetch the rules from current schema
        $ruleString.= "\n\t\t),";

        return $ruleString;
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