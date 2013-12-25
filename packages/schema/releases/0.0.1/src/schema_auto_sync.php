<?php
namespace Schema\Src;

Abstract Class Schema_Auto_Sync {

	protected $_escape_char	= '`%s`';	// Escape character

	public $queryWarning;				// Javascript query alert for dangerous mysql operations.
	public $db;							// database object
	public $tablename;					// tablename
	public $modelName;					// modelname
	public $schemaObject;				// schema class object
	public $schemaName = null;			// lowercase schema name
	public $dbSchema   = array();		// database schema array
	public $fileSchema = array();		// stored file schema array
	public $currentFileSchema = array();// current ( pure ) fileschema array
	public $schemaDiff = array();		// last schema output after that the sync

	// --------------------------------------------------------------------

	/**
	 * Constructor
	 * 
	 * @param string $schemaDBContent database schema content
	 * @param string $schemaObject schema Object
	 */
	public function __construct($schemaDBContent, \Schema $schemaObject)
	{
		$this->tablename    = $schemaObject->getTableName(); // Set tablename
		$this->modelName    = $schemaObject->getModelName(); // Set modelname
		$this->schemaName   = strtolower($this->tablename);  // set schema name

		//----------- Use Shmop Shared Memory ---------//
		
		// Look at the memory if memory schema exists 
		// read from it to fast file read
		
		$shmop = new \Shmop;
		$memSchema = $shmop->get($this->schemaName);
		
		if($memSchema !== null)
		{
            eval(unserialize($memSchema)); // Get current schema from memory to fast file write

			$variableName = $this->schemaName;
            $fileSchema   = $$variableName;

			// $shmop->delete($this->schemaName);  	// Delete memory segment
		} 
		else 
		{
			$fileSchema = getSchema($this->schemaName); // Get current schema
		}
	    						
		//----------- Use Shmop Shared Memory ---------//

	    $colprefix = $fileSchema['*']['colprefix'];
		unset($fileSchema['*']);  // Get only fields no settings

		$newFileSchema = array();
		foreach($fileSchema as $k => $v)
		{
			$newFileSchema[$colprefix.$k] = $v;
		}

	    $this->currentFileSchema = $fileSchema; // Backup Current Schema

		eval('$databaseSchema = array('.$schemaDBContent.');');  // Active Schema coming from database
		unset($databaseSchema['*']);


		$this->dbSchema     = $this->_reformatSchemaTypes($databaseSchema);  // Render schema, fetch just types.
		$this->fileSchema   = $this->_reformatSchemaTypes($newFileSchema);  // Get just types 
		$this->db           = $schemaObject->dbObject;     // Database Object
		$this->schemaObject = $schemaObject; // Schema Object
		$this->debug        = false;
	}

	// --------------------------------------------------------------------

	/**
	 * Check fileSchema & dbSchema 
	 * collisions.
	 * 
	 * @return boolean
	 */
	public function collisionExists()
	{
		$diff = $this->getSchemaDiffArray();
		$colCount = sizeof($diff);

		$sumChanges = 0;
		foreach ($this->getSchemaDiffArray() as $k => $v)
		{
			$sumChanges += sizeof($v);
		}

		if($sumChanges == $colCount)  // no collisions
		{
			return false;
		}

		return true; // Yes, we have schema collisions
	}

	// --------------------------------------------------------------------

	/**
	 * Run the class
	 * 
	 * @return boolean
	 */
	public function run()
	{
		$this->_dbDiff();
		$this->_schemaDiff();
		$this->_isPostCommand(); // check the post action the run the command

		if($this->schemaObject->debug)
		{
			echo $this->schemaObject->getDebugOutput();
		}

		$this->schemaObject->runQuery();  // it check $_POST['query'] input & run sql query.
	}

	// --------------------------------------------------------------------

	/**
	 * Calculate database differencies
	 * 
	 * @return void
	 */
	private function _dbDiff()
	{
		foreach($this->dbSchema as $k => $v)
		{
			if( ! isset($this->fileSchema[$k])) // Sync column names
			{
				$this->schemaDiff[$k] = array(
					'new_types' => $v,
					'options' => array(
						'drop',
						'add-to-file'
						),
					);
			}

			if(array_key_exists($k, $this->fileSchema)) // Sync column types
			{
				$dbTypes 	 = explode('|', trim($this->dbSchema[$k], '|'));
				$schemaTypes = explode('|', trim($this->fileSchema[$k], '|'));
				$diffMatches = array_diff($dbTypes, $schemaTypes);
				
				if(sizeof($diffMatches) > 0)
				{
					foreach ($diffMatches as $diffVal)
					{
						$this->schemaDiff[$k][] = array(
						'update_types' => $diffVal,
						'options' => array(
							'drop',
							'add-to-file'
							),
						);
					}
				}

				/* Search data types and remove unecessary buttons. ( REMOVE DROP  BUTTON )*/

				$result 	= preg_replace('/(\(.*?\))/','', $this->fileSchema[$k]);  // Remove data type brackets from file schema
				$grep_array = preg_grep('/'.$result.'/',$this->dataTypes); // Search data type exists

				// If at least one data type not exists in the schema file
				// don't show "drop" button to users
				// users must be add a data type in the fileschema field.

				if(sizeof($grep_array) == 0) // If data type not exists 
				{
					 unset($this->schemaDiff[$k][0]['options'][0]); // REMOVE DROP  BUTTON 
				}

				// Array
				// (
				// )
				// Array
				// (
				//     [26] => _text
				// )
				// Array
				// (
				//     [18] => _varchar
				// )
			}

		}
	}

	// --------------------------------------------------------------------

	/**
	 * Calculate schema differencies
	 * 
	 * @return void
	 */
	private function _schemaDiff()
	{
		foreach($this->fileSchema as $k => $v)
		{
			if( ! isset($this->dbSchema[$k]))	// Sync column names
			{
				$result = preg_replace('/(\(.*?\))/','', $v); // Remove data type brackets from file schema

				if ( ! preg_grep('/'.$result.'/',$this->dataTypes))  // Search data type exists
				{
					$this->schemaDiff[$k] = array(
					'new_types' => $v,
					'options' => array(
						'remove-from-file',
						'add-to-db'
						),
					'errors' => "	<span style='color:red;font-size:12px'>You have to define a datatype in your file schema.</span>"
					);
				}
				else
				{
					$this->schemaDiff[$k] = array(
						'new_types' => $v,
						'options' => array(
							'remove-from-file',
							'add-to-db'
							),
						);
				}
			}
			else 
			{
				$this->schemaDiff[$k]['types'] = $v;  // * Add current columns
			}

			if(array_key_exists($k, $this->dbSchema)) // Sync column types
			{
				$dbTypes 	 = explode('|', trim($this->dbSchema[$k], '|'));
				$schemaTypes = explode('|', trim($this->fileSchema[$k], '|'));

				$diffMatches = array_diff($schemaTypes, $dbTypes);

				if(sizeof($diffMatches) > 0)
				{
					foreach ($diffMatches as $diffVal)
					{
						$this->schemaDiff[$k][] = array(
						'update_types' => $diffVal,
						'options' => array(
							'remove-from-file',
							'add-to-db'
							),
						);
					}

				}
				
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
		$label = (isset($this->currentFileSchema[$key]['label'])) ? $this->currentFileSchema[$key]['label'] : $this->_createLabel($key);
		$rules = (isset($this->currentFileSchema[$key]['rules'])) ? $this->currentFileSchema[$key]['rules'] : '';

		$ruleString = "\n\t'$key' => array(";
		$ruleString.= "\n\t\t'label' => '$label',";  // fetch label from current schema

		if(empty($newType))
		{
			if (preg_match('/(_enum)(\(.*?\))/',$types, $match)) // if type is enum create enum field as an array
			{
			 	$enumStr  = $match[0];  // _enum("","")
				$enum 	  = $match[1];  // _enum
				$enumData = $match[2];  // ("","")

				$types = preg_replace('/'.preg_quote($enumStr).'/', '_enum', $types);
				
				$ruleString .= "\n\t\t'_enum' => array(";	// render enum types
			 	foreach(explode(',', trim(trim($enumData, ')'),'(')) as $v)
			 	{
			 		$ruleString .= "\n\t\t\t".str_replace('"',"'",$v).","; // add new line after that for each comma
			 	}
				$ruleString .= "\n\t\t),";
			}

			$ruleString.= "\n\t\t'types' => '".$types."',";
		}
		else 
		{
			$typeStr 	= (is_array($types)) ? $newType : $types.'|'.$newType; // new field comes as array data we need to prevent it.
			$ruleString.= "\n\t\t'types' => '".$typeStr."',";
		}

		$ruleString.= "\n\t\t'rules' => '$rules',"; // fetch the rules from current schema
		$ruleString.= "\n\t\t),";

		return $ruleString;
	}

	// --------------------------------------------------------------------

	/**
	 * Create column label automatically
	 * 
	 * @param  string $field field name
	 * @return string column label
	 */
	private function _createLabel($field)
	{
		$exp = explode('_', $field); // explode underscores ..

		if($exp)
		{
			$label = '';
			foreach($exp as $val)
			{
				$label.= ucfirst($val).' ';
			}
		} 
		else 
		{
			$label = ucfirst($field);
		}

		return trim($label);
	}

	// --------------------------------------------------------------------

	/**
	 * Get the schema differencies in array format
	 * 
	 * @return string
	 */
	public function getSchemaDiffArray()
	{
		return $this->schemaDiff;
	}

	// --------------------------------------------------------------------

	/**
	* Convert output
	*   Array
	*  (
	*    [id] => _not_null|_primary_key|_unsigned|_int(11)|_auto_increment
	*    [cities] => _not_null|_default("banana")|_enum("apple","orange","banana")
	*    [datetime] => _not_null|_datetime
	*    [email] => _null|_char(160)
	*   );
	* @param  array  $schemaArray
	* @return array              
	*/
	public function _reformatSchemaTypes($schemaArray = array())
	{
		$schema = array();
		foreach($schemaArray as $key => $val)
		{
			$val['types'] = (isset($val['types'])) ? $val['types'] : '';
	
			if(isset($schemaArray[$key]['_enum']))
			{
				$enum = '';
				foreach($schemaArray[$key]['_enum'] as $enumVal)
				{
					$enum.= '"'.$enumVal.'",';
				}
				
				$enum = trim($enum, ',');
				$schema[$key] = preg_replace('/_enum/', "_enum($enum)", $val['types']);
			}
			elseif(isset($schemaArray[$key]['_set']))
			{
				$set = '';
				foreach($schemaArray[$key]['_set'] as $enumVal)
				{
					$set.= '"'.$enumVal.'",';
				}

				$set = trim($set, ',');
				$schema[$key] = preg_replace('/_set/', "_set($set)", $val['types']);
			}
			else 
			{
				$schema[$key] = $val['types'];
			}
		}

		return $schema;
	}

	// --------------------------------------------------------------------

	/**
	 * Display all html output of 
	 * the Sync Feature.
	 * 
	 * @return string
	 */
	public function output()
	{
		$output = $this->schemaObject->getOutput(); // Sync diff ooutput

		if( ! empty($output)) // write output to schema file
		{
			$this->schemaObject->writeToFile($this->schemaObject->getOutput(), $this->schemaObject->getPrefix());
		}
		
		$sync_html = new Schema_Auto_Sync_Html($this, $this->schemaObject);
		return $sync_html->writeOutput();
	}

	// --------------------------------------------------------------------

	/**
	 * Use escape character
	 * 
	 * @param  string $val escaped value
	 * @return string
	 */
	public function quoteValue($val)
	{
		return sprintf($this->_escape_char, $val);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Remove underscored from data types
	 * 
	 * @param  string $str
	 * @return string
	 */
	public function removeUnderscore($str)
	{
		return str_replace('_',' ',$str);
	}

}

/* End of file schema_auto_sync.php */
/* Location: ./packages/schema/releases/0.0.1/schema_auto_sync.php */