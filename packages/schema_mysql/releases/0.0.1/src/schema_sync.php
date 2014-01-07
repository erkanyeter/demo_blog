<?php
namespace Schema_Mysql\Src;

Class Schema_Sync extends \Schema\Src\Schema_Auto_Sync {

	public $dataTypes  = array(  // defined mysql data types
	 	'_bit',
		'_tinyint',
		'_smallint',
		'_mediumint',
		'_int',
		'_integer',
		'_bigint',
		'_real',
		'_double',
		'_float',
		'_decimal',
		'_numeric',
		'_date',
		'_time',
		'_timestamp',
		'_datetime',
		'_year',
		'_char',
		'_varchar',
		'_binary',
		'_varbinary',
		'_tinyblob',
		'_blob',
		'_mediumblob',
		'_longblob',
		'_tinytext',
		'_text',
		'_mediumtext',
		'_longtext',
		'_enum',
		'_set');
	public $attributeTypes = array(
		'_null',
		'_not_null',
		'_default',
		'_unsigned',
		'_unsigned_zerofill',
		'_key',
		'_foreign_key',
		'_unique_key',
		'_primary_key',
		'_auto_increment',
		);

	public $colprefix;

	/**
	 * Constructor
	 * 
	 * @param string $schemaDBContent database schema content
	 * @param string $schemaObject schema Object
	 */
	public function __construct($schemaDBContent, \Schema $schemaObject)
	{
		parent::__construct($schemaDBContent, $schemaObject);
		
		$this->colprefix = $schemaObject->getPrefix();
	}

	// --------------------------------------------------------------------

	/**
	 * Post command action
	 * 
	 * @return boolean
	 */
	public function _isPostCommand()
	{
		if(isset($_POST['lastSyncCommand'])) // If we have post action with post command
		{
			$lastCommand = explode('|', $_POST['lastSyncCommand']);
			$lastFunc 	 = $_POST['lastSyncFunc'];

			if($lastFunc == 'addKey' OR $lastFunc == 'removeKey') // add / remove attribute
			{
				$count 	  = count($lastCommand);
				$colName  = $lastCommand[0]; // column name
				$command  = $lastCommand[$count - 1];

				unset($lastCommand[0]);
				unset($lastCommand[$count - 1]);

				$colType = implode('|', $lastCommand) ; // _null|_text|..........
				$isNew   = true;
			}
			else
			{
				$colName = $lastCommand[0]; // column name
				$colType = $lastCommand[1]; // _not_null
				$command = $lastCommand[2]; // drop
				$isNew   = (isset($lastCommand[3]) AND $lastCommand[3] == 'new') ? true : false; // is new field ?
			}
		
			$startArray = '$'.$this->schemaName.' = array(';
			$endArray   = ');';
			$disabled 	= false;
			switch ($command)
			{
				case 'drop':   // Drop Type from Database
					
					$colTypeArray = explode('|', $colType);
					
					if ($isNew == true AND count($colTypeArray) > 1)  // If user want to drop this field run this process & coltype has more one
					{
						$dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP '.$this->quoteValue($colName);
					}
					elseif($isNew == true AND count($colTypeArray) == 1) // If this field is not exist in FileSchema and coltype number is one
					{
						$unbracketsColType   = preg_replace('#(\(.*?\))#','', $colType); // Post data column types
						$explodedColType     = explode('|',$unbracketsColType);
						
						$unbracketsDbColType = preg_replace('#(\(.*?\))#','', $this->dbSchema[$colName]); // Db schema column types
						$explodedDbColTypes  = explode('|',$unbracketsDbColType);

						$dbcolKeys           = preg_grep('#'.$unbracketsDbColType.'#',$this->dataTypes); // check data type exists in DbSchema+
						$dbColKeyValue 		 = array_values($dbcolKeys)[0];
						$colkey              = array_search($dbColKeyValue,$explodedDbColTypes);  // Array Position 

						$dbArray             = explode('|',$this->dbSchema[$colName]);
						$dbCommand           = 'ALTER TABLE '.$this->quoteValue($this->schemaName);	// Default sql command prefix

						$dbArray[$colkey]    = $this->removeUnderscore($dbArray[$colkey]); // Remove Underscores from datatypes

						$dbArray[$colkey]    = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Don't uppercase data inside brackets
					        return strtoupper($match[0]); 
					    }, $dbArray[$colkey]);

							switch ($explodedColType[0]) // Types
							{
								case '_null':
									$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).' '.$dbArray[$colkey].' NOT NULL';
									break;

								case '_auto_increment':
									$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).' '.$dbArray[$colkey].' NOT NULL';
									break;

								case '_key':
									$dbCommand .= ' DROP INDEX '.$this->quoteValue($colName);
									break;

								case '_unsigned':
									$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).$dbArray[$colkey].' UNSIGNED';
									break;

								case '_unique_key' : 
									$dbCommand .= ' DROP INDEX '.$this->quoteValue($colName);
									break;

								case '_primary_key':
									$dbCommand .= ' DROP PRIMARY KEY ';
									break;

								case '_not_null':
									$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).$dbArray[$colkey].' NULL';
									break;

								case '_default':
									$dbCommand .= ' ALTER COLUMN '.$this->quoteValue($colName).' DROP DEFAULT';
									break;
								default :
									$dbCommand .= ' DROP '.$this->quoteValue($colName);

									$this->queryWarning = 'This column will be deleted from the database, are you sure you want to continue ?';
									break;
							}
					}
					else // If this field exists in FileSchema
					{
						$unbracketsColType     = preg_replace('#(\(.*?\))#','', $colType); // Post data column types
						$explodedColType       = explode('|',$unbracketsColType);
						
						$unbracketsFileColType = preg_replace('#(\(.*?\))#','', $this->fileSchema[$colName]); // File schema column types
						$explodedFileColTypes  = explode('|',$unbracketsFileColType);
					
						$dbcolKeys             = preg_grep('#'.$unbracketsFileColType.'#',$this->dataTypes); // Check data type exists in FileSchema

						if (count($dbcolKeys) > 0) // Datatype exist in schema
						{
							$fileSchemaArray = explode('|',$this->fileSchema[$colName]);
							$colkey          = array_search(array_values($dbcolKeys)[0],$explodedFileColTypes);// ColType array position in file schema

							if ($colKeys = preg_grep('#'.$explodedColType[0].'#',$this->dataTypes)) // If datatype
							{
								$dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' ';
								$fileSchemaArray[$colkey] = $this->removeUnderscore($fileSchemaArray[$colkey]); // Remove Underscores from datatypes
								
								$fileSchemaArray[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Don't uppercase data inside brackets
								return strtoupper($match[0]); 
								}, $fileSchemaArray[$colkey]);

								$dbCommand.= 'MODIFY COLUMN '.$this->quoteValue($colName).$fileSchemaArray[$colkey];
							}
							else
							{
								$unbracketsDbColType = preg_replace('#(\(.*?\))#','', $this->dbSchema[$colName]); // Db schema column types without brackets
								$explodedDbColTypes  = explode('|',$unbracketsDbColType); // Get column types from DBSchema to array 

								$dbcolKeys        = preg_grep('#'.$unbracketsFileColType.'#',$this->dataTypes);
								$dbArray          = explode('|',$this->dbSchema[$colName]);
								$dbColKeyValue 	  = array_values($dbcolKeys)[0];
								$colkey           = array_search($dbColKeyValue,$explodedDbColTypes);
								$dbArray[$colkey] = $this->removeUnderscore($dbArray[$colkey]); // Remove Underscores from datatypes
								
								$dbArray[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Dont uppercase data inside brackets
									return strtoupper($match[0]); 
								}, $dbArray[$colkey]);

								$dbCommand        = 'ALTER TABLE '.$this->quoteValue($this->schemaName);  // Default sql command prefix

								switch ($explodedColType[0])	// types
								{
									case '_null':
										$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).' '.$dbArray[$colkey].' NOT NULL';
										break;

									case '_auto_increment':
										$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).' '.$dbArray[$colkey].' NOT NULL';
										break;

									case '_key':
										$dbCommand .= ' DROP INDEX '.$this->quoteValue($colName);
										break;

									case '_unsigned':
										$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).$dbArray[$colkey].' UNSIGNED';
										break;

									case '_unique_key' : 
										$dbCommand .= ' DROP INDEX '.$this->quoteValue($colName);
										break;

									case '_primary_key':
										$dbCommand .= ' DROP PRIMARY KEY ';
										break;

									case '_not_null':
										$dbCommand .= ' MODIFY COLUMN '.$this->quoteValue($colName).$dbArray[$colkey].' NULL';
										break;

									case '_default':
										$dbCommand .= ' ALTER COLUMN '.$this->quoteValue($colName).' DROP DEFAULT';
										break;
								}
							}
						}
						else
						{
							$dbCommand = '<span style="color:red">You can not drop a data type without define a new in '.ucfirst($this->schemaName).' Schema.</span>';
							$disabled = true;
						}	
					}

					echo $this->schemaObject->displaySqlQueryForm($dbCommand, $this->queryWarning,$disabled); // Show sql query to developer to confirmation.

					break;

				case 'add-to-db': 	//  Add Type to Database
					
					$schemaKeys = explode('|',$colType);
					
					if (isset($this->dbSchema[$colName])) // Already exists 
					{
						$dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($colName);

						$unbracketsColType = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets coming from $_POST
						$unbracketsColTypes = explode('|',$unbracketsColType);
						
						$schemaKeys = explode('|',$this->dbSchema[$colName]);
						
						$unbracketsDBColType = preg_replace('#(\(.*?\))#s','', $this->dbSchema[$colName]);// Get pure DB column type withouth brackets
						$unbracketsDBColTypes = explode('|',$unbracketsDBColType);

						$dbcolKeys = preg_grep('#'.$unbracketsDBColType.'#',$this->dataTypes); // Data Type Confirmation

						$dbColKeyValue = array_values($dbcolKeys)[0];
						$colkey        = array_search($dbColKeyValue,$unbracketsDBColTypes); // Find the location of columnType  in array of dbSchema

						if ($columnType = preg_grep('#'.$unbracketsColType.'#',$this->dataTypes))  // If colname exists and has a datatype in DBSchema Change Datatypes 
						{
							$schemaKeys[$colkey] = $this->removeUnderscore($colType);

						    $schemaKeys[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Change Datatypes 
						        return strtoupper($match[0]); 
						    }, $schemaKeys[$colkey]);

							$dbCommand .= $schemaKeys[$colkey];
						}
						else
						{	
							$schemaKeys[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
						        return strtoupper($match[0]); 
						    }, $this->removeUnderscore($schemaKeys[$colkey]));
							$dbCommand .= $schemaKeys[$colkey];

							switch ($unbracketsColTypes[0]) 
							{
								case '_null':
									if ( ! isset($dbCommands[2])) 
									{
										$dbCommands[1] = strtoupper($this->removeUnderscore($unbracketsColTypes[0]));
										unset($dbCommands[3]);
									}
									break;

								case '_auto_increment':
									$dbCommands[1] = ' NOT NULL';
									$dbCommands[2] = ' AUTO_INCREMENT';
									break;

								case '_key':
									$dbCommands[6] = ',ADD INDEX ('.$this->quoteValue($colName).')';
									break;

								case '_unsigned':
									$dbCommands[0] = strtoupper($this->removeUnderscore($unbracketsColTypes[0]));
									break;

								case '_unique_key' : 
									$colType = $this->removeUnderscore($colType);
									if (strpos($colType, ')(') > 0) 
									{
										$colType = trim($colType,")");
										$exp = explode(')(', $colType);
										$keyIndex  = $exp[0];
										unset($exp[0]);
										$implodeKeys = array();
										foreach($exp as $item)
										{
											if(strpos($item, ',') !== false)
											{
												foreach (explode(',', $item) as $k => $v)
												{
													$implodeKeys[] = $this->quoteValue($this->colprefix.$v);
												}

											} else 
											{
												$implodeKeys = array($this->quoteValue($this->colprefix.$item));
											}
										}
									}
									$dbCommands[5] = ',ADD UNIQUE INDEX ('.$this->quoteValue($colName).') ('.implode($implodeKeys, ",").')';
									break;

								case '_primary_key':
									$dbCommands[4] = ',ADD PRIMARY KEY ('.$this->quoteValue($colName).')';
									break;

								case '_not_null':
									$dbCommands[1] = strtoupper($this->removeUnderscore($unbracketsColTypes[0]));
									break;

								case '_default':
										$colType = $this->removeUnderscore($colType);

										preg_match('#\((([^\]])*)\)#',$colType, $matches);

										$dbCommands[1] = 'NOT NULL';
										$dbCommands[3] = 'DEFAULT '.$matches[1];
									break;
							}
						}
					}
					else 	// New 
					{
						$unbracketsColType = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
						$unbracketsColTypes = explode('|',$unbracketsColType);

						if ( ! $columnType = preg_grep('#'.$unbracketsColType.'#', $this->dataTypes))
						{
							$dbCommand = '<span style="color:red;">You have to define a datatype in your file schema.</span>';
							$disabled = true;
						}
						else
						{

							$colKeyValue = array_values($columnType)[0];
							$colkey 	 = array_search($colKeyValue, $unbracketsColTypes);//Get position in Array
							$columnType  = $this->removeUnderscore($schemaKeys[$colkey]);
							$dbCommand   = 'ALTER TABLE '.$this->schemaName;
							$schemaKeys  = explode('|', $colType);
							unset($schemaKeys[$colkey]);
							unset($unbracketsColTypes[$colkey]);
							foreach ($unbracketsColTypes as $key => $value)
							{
								switch ($value) 
								{
									case '_null':
										if ( ! isset($dbCommands[2])) 
										{
											$dbCommands[1] = strtoupper($this->removeUnderscore($value));
											unset($dbCommands[3]);
										}
										break;

									case '_auto_increment':
										$dbCommands[1] = 'NOT NULL';
										$dbCommands[2] = ' AUTO_INCREMENT';
										break;

									case '_key':
										$dbCommands[6] = ',ADD INDEX ('.$this->quoteValue($colName).')';
										break;

									case '_unsigned':
										$dbCommands[0] = strtoupper($this->removeUnderscore($value));
										break;

									case '_unique_key' : 
										$dbCommands[5] = ',ADD UNIQUE INDEX ('.$this->quoteValue($colName).')';
										break;

									case '_primary_key':
										$dbCommands[4] = ',ADD PRIMARY KEY ('.$this->quoteValue($colName).')';
										break;

									case '_not_null':
										$dbCommands[1] = strtoupper($this->removeUnderscore($value));
										break;

									case '_default':
										$schemaKeys[$key] = $this->removeUnderscore($schemaKeys[$key]);

										preg_match('#\((([^\]])*)\)#',$schemaKeys[$key],$matches);

										$dbCommands[1] = 'NOT NULL';
										$dbCommands[3] = 'DEFAULT '.$matches[1];
										break;
								}
							}
						    $columnType = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
						        return strtoupper($match[0]); 
						    }, $columnType);
							
							$dbCommand .= ' ADD COLUMN '.$this->quoteValue($colName).$columnType;

						}	
					}

					for ($i = 0; $i < 7; $i++) 
					{ 
						if (isset($dbCommands[$i])) 
						{
							$dbCommand.= ' '.$dbCommands[$i];
						}
					}

					echo $this->schemaObject->displaySqlQueryForm($dbCommand, $this->queryWarning,$disabled); // Show sql query to developer to confirmation.

					break;

				case 'add-to-file':  // Add item to valid schema // Rebuild Schema Array & Write To File

					$ruleString = '';
					if( $isNew AND ! array_key_exists($colName, $this->fileSchema) AND isset($colType)) // new "field"
					{
						$colTypeArray = explode('|', $colType);

						for ($i = 0; $i < count($colTypeArray); $i++) 
						{ 
							$schemaKeys[] = $colTypeArray[$i];
						}
 					}
					else
					{
						$schemaKeys = explode('|', $this->fileSchema[$colName]);

						$unbracketsFileColType 	= preg_replace('#(\(.*?\))#','', $this->fileSchema[$colName]); // Get pure column type withouth brackets
						$unbracketsFileColTypes = explode('|',$unbracketsFileColType); // Array of unbracketsFileColType

						if ($key = preg_grep('#'.$unbracketsFileColType.'#',$this->dataTypes)) // Search into datatypes with matches
						{
							$colFileKeyValue = array_values($key)[0];
							$colkey  = array_search($colFileKeyValue,$unbracketsFileColTypes); // Find datatype location in matches 
						}

						$unbracketsColType 	= preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
						$unbracketsColTypes = explode('|',$unbracketsColType); // Create pure column types without brackets and variables 
						
						switch ($unbracketsColTypes[0])	// types
						{
							case '_null':
									if (is_numeric(($key = array_search('_not_null',$unbracketsFileColTypes)))) // if not null exists change it
									{
										$schemaKeys[$key] = '_null' ;
									}
									elseif (is_numeric(($key = array_search('_null',$unbracketsFileColTypes)))) // if null exists change it
									{
										$schemaKeys[$key] = '_null' ;
									}
									else
									{

										$schemaKeys[] = $colType;	
									}
								break;

							case '_not_null':
									if (is_numeric(($key = array_search('_null',$unbracketsFileColTypes)))) // if null exists change it
									{
										$schemaKeys[$key] = '_not_null' ;
									}
									elseif (is_numeric(($key = array_search('_not_null',$unbracketsFileColTypes)))) // if _not_null exists change it
									{
										$schemaKeys[$key] = '_not_null' ;
									}
									else
									{
										$schemaKeys[] = $colType;	
									}
								break;

							case '_key':
								is_numeric(($key = array_search('_key',$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType;
								break;

							case '_unsigned':
								is_numeric(($key = array_search('_unsigned',$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType;
								break;

							case '_unique_key' : 
								is_numeric(($key = array_search('_unique_key',$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType;
								break;

							case '_primary_key':
								is_numeric(($key = array_search('_primary_key',$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType;
								break;

							case '_default':
								is_numeric(($key = array_search('_default',$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType; 
								break;

							case '_auto_increment':
								is_numeric(($key = array_search('_auto_increment',$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType; 
								break;

							default:
								 if (isset($colkey)) 
								 {
									$schemaKeys[$colkey] = $colType ;
								 }
								 else
								 {
								 	$schemaKeys[] = $colType;
								 }
								break;
						}
					}
					
					$this->fileSchema[$colName] = trim(implode('|',$schemaKeys),'|');
					foreach($this->fileSchema as $k => $types)
					{
						$ruleString.= $this->schemaObject->driver->buildSchemaField($k, $types);
					}
					
					$this->schemaObject->setDebugOutput($ruleString);
					$this->schemaObject->setOutput($ruleString);

					break;
				
				case 'remove-from-file':

					if(array_key_exists($colName, $this->fileSchema)) // new Field
					{
						$ruleString = '';
						$colTypeArray = explode('|',$colType);

						if (count($colTypeArray) > 1 ) 
						{
							unset($this->fileSchema[$colName]);
						}
						else
						{
							$schemaKeys = explode('|',$this->fileSchema[$colName]);
							unset($schemaKeys[array_search($colType, $schemaKeys)]);
							
							if (count($schemaKeys) > 0)
							{
								$this->fileSchema[$colName] = implode('|',$schemaKeys);
							}
							else
							{
								unset($this->fileSchema[$colName]);
							}
						}

						foreach($this->fileSchema as $k => $types)
						{
							$ruleString.= $this->schemaObject->driver->buildSchemaField($k, $types);
						}

						$this->schemaObject->setDebugOutput($ruleString);
						$this->schemaObject->setOutput($ruleString);
					}

					break;
			}
		}
	}
}

/* End of file schema_sync.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/src/schema_sync.php */