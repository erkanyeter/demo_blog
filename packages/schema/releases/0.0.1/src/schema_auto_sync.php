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

		$fileSchema = getSchema($this->schemaName); // Get current schema
	    					
	    $colprefix = $fileSchema['*']['colprefix'];
		unset($fileSchema['*']);  // Get only fields no settings

		$newFileSchema = array();
		foreach($fileSchema as $k => $v)
		{
			$newFileSchema[$colprefix.$k] = $v;
		}

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

				$result 	= preg_replace('#(\(.*?\))#','', $this->fileSchema[$k]);  // Remove data type brackets from file schema
				$grep_array = preg_grep('#'.$result.'#',$this->dataTypes); // Search data type exists

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
				$result = preg_replace('#(\(.*?\))#','', $v); // Remove data type brackets from file schema

				if ( ! preg_grep('#'.$result.'#',$this->dataTypes))  // Search data type exists
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
    					if (substr($diffVal, 0,1) === '_') // check is it datatype.
                        {
                            $this->schemaDiff[$k][] = array(
                            'update_types' => $diffVal,
                            'options' => array(
                                'remove-from-file',
                                'add-to-db'
                                ),
                            );
                        }
                        else  // if not show just remove file option
                        {
                            $this->schemaDiff[$k][] = array(
                            'update_types' => $diffVal,
                            'options' => array(
                                'remove-from-file'
                                ),
                            );
                        }
					}

				}
				
			}
		}
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
				$schema[$key] = preg_replace('#_enum#', "_enum($enum)", $val['types']);
			}
			elseif(isset($schemaArray[$key]['_set']))
			{
				$set = '';
				foreach($schemaArray[$key]['_set'] as $enumVal)
				{
					$set.= '"'.$enumVal.'",';
				}

				$set = trim($set, ',');
				$schema[$key] = preg_replace('#_set#', "_set($set)", $val['types']);
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
			$this->schemaObject->writeToFile($output, $this->schemaObject->getPrefix());
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