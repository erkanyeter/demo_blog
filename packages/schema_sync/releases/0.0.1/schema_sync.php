<?php

Class Schema_Sync {

    protected $_escape_char    = '`%s`';    // Escape character

    public $queryWarning;               // Javascript query alert for dangerous mysql operations.
    public $db;                         // database object
    public $tablename;                  // tablename
    public $modelName;                  // modelname
    public $schemaObject;               // schema class object
    public $schemaName = null;          // lowercase schema name
    public $dbSchema   = array();       // database schema array
    public $fileSchema = array();       // stored file schema array
    public $schemaDiff = array();       // last schema output after that the sync

	public $dataTypes  = array(  		// defined mysql data types
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

    // --------------------------------------------------------------------

    /**
     * Constructor
     * 
     * @param string $schemaDBContent database schema content
     * @param string $schemaObject schema Object
     */
    public function __construct($schemaDBContent, $schemaObject)
    {
        $this->tablename    = $schemaObject->getTableName(); // Set tablename
        $this->modelName    = $schemaObject->getModelName(); // Set modelname
        $this->schemaName   = strtolower($this->tablename);  // set schema name

        $fileSchema = getSchema($this->schemaName); // Get current schema
        
        unset($fileSchema['*']);  // Get only fields no settings

        $newFileSchema = array();
        foreach($fileSchema as $k => $v)
        {
            $newFileSchema[$k] = $v;
        }

        // exit($schemaDBContent);

        eval('$databaseSchema = array('.$schemaDBContent.');');  // Active Schema coming from database
        unset($databaseSchema['*']);

        $this->dbSchema     = $this->_reformatSchemaTypes($databaseSchema); // Render schema, fetch just types.
        $this->fileSchema   = $this->_reformatSchemaTypes($newFileSchema, true);  // Get just types 

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
    * Convert output && remove unnecessary pipes ..
    * 
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
    public function _reformatSchemaTypes($schemaArray = array(), $quote = false)
    {
        $schema = array();
        foreach($schemaArray as $key => $val)
        {
            $val['types'] = (isset($val['types'])) ? $val['types'] : '';

            $val['types'] = preg_replace('#(\|+|\|\s+)(?:\|)#', '|', $val['types']);  // remove unnecessary pipes ..
            $val['types'] = preg_replace('#["]+#', '', $val['types']); // remove double " " quotes ...
            $val['types'] = preg_replace('#(_enum)(\(.*?\))#', "_enum", $val['types']);//if user insert enum data in types we remove it

            if(isset($schemaArray[$key]['_enum']))
            { 
                $enum = '';
                foreach($schemaArray[$key]['_enum'] as $enumVal)
                {
                    if($quote)
                    {
                        $enumVal = addslashes($enumVal);  // add slashes for ' quote
                    }

                    $enum.= "'".$enumVal."',";
                }
                
                $enum = trim($enum, ',');
                $schema[$key] = preg_replace('#_enum#', "_enum($enum)", $val['types']);
          
            }
            elseif(isset($schemaArray[$key]['_set']))
            {
                $set = '';
                foreach($schemaArray[$key]['_set'] as $enumVal)
                {
                    if($quote)
                    {
                        $enumVal = addslashes($enumVal); // add slashes for ' quote
                    }

                    $set.= "'".$enumVal."',";
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
                $dbTypes      = explode('|', trim($this->dbSchema[$k], '|'));
                foreach ($dbTypes as $type) 
                {
                    $this->schemaDiff[$k]['options'] = array(
                        'types' => $v,
                        'drop',
                        'add-to-file',
                        'rename'
                        );
                    $this->schemaDiff[$k]['new_types'][] = array(
                    'type' => $type,
                    'types' => $v,
                    'options' => array(
                        'drop',
                        'add-to-file',
                        'rename'
                        ),
                    );   
                }
                
            }

            if(array_key_exists($k, $this->fileSchema)) // Sync column types
            {
                $dbTypes     = explode('|', trim($this->dbSchema[$k], '|'));
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

                $result     = preg_replace('#(\(.*?\))#','', $this->fileSchema[$k]);  // Remove data type brackets from file schema
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
    public function _schemaDiff()
    {
        $allDbData      = implode('|',$this->dbSchema); // get all db data
        $allDbData      = preg_replace('#(\(.*?\))#','', $allDbData);
        $allDbDataArray = explode('|',$allDbData);

        foreach($this->fileSchema as $k => $v)
        {
            if( ! isset($this->dbSchema[$k]))    // Sync column names
            {
                $result = preg_replace('#(\(.*?\))#','', $v); // Remove data type brackets from file schema

                $dataTypeArray = $this->getDataTypeArray($result,$this->dataTypes);
                $dataTypeCount = sizeof($dataTypeArray);
                $dataTypeArray = array_values($dataTypeArray);
                $newAttributes = explode('|', trim($result, '|'));        

                $v = explode('|', trim($v, '|'));

                if ($dataTypeCount == 0)  // Datatype must be unique and valid
                {
                    $i = 0;
                    foreach($newAttributes as $types)
                    {
                        $this->schemaDiff[$k][] = array(
                        'new_types' => $v[$i],
                        'options' => array(
                            'remove-from-file'
                            ),
                        'errors' => "    <span style='color:red;font-size:12px'>You have to define a valid datatype in your file schema.</span>"
                        );
                        $i++;
                    }
                }
                elseif ($dataTypeCount > 1) 
                {
                    $i = 0;
                    foreach($newAttributes as $types)
                    {
                        $this->schemaDiff[$k][] = array(
                            'new_types' => $v[$i],
                            'options' => array(
                                'remove-from-file',
                                ),
                            'errors' => "    <span style='color:red;font-size:12px'>You can't define more than one datatype in your file schema.</span>"
                            );
                        $i++;
                    }
                }
                else
                {
                    // @todo
                    // $newAttributes = preg_replace('#\b'..'\b#', '', $newAttributes);
                    
                    $i = 0;
                    foreach($newAttributes as $type)
                    {
                        if (in_array($type,$this->attributeTypes) || in_array($type, $this->dataTypes))
                        {
                            $this->schemaDiff[$k]['options'] = array(
                                'types' => implode('|', $v),
                                'remove-from-file',
                                'add-to-db',
                                
                                );
                            $this->schemaDiff[$k]['new_types'][] = array(
                            'type' => $v[$i],
                            'types' => implode('|', $v),
                            'options' => array(
                                'remove-from-file',
                                'add-to-db'
                                ),
                            );
                        }
                        else
                        {
                            $this->schemaDiff[$k]['options'] = array(
                                'types' => implode('|', $v),
                                'remove-from-file',
                                'add-to-db',
                                'class' => 'syncerror'
                                );
                            $this->schemaDiff[$k]['new_types'][] = array(
                            'type' => $v[$i],
                            'types' => implode('|', $v),
                            'options' => array(
                                'remove-from-file',
                                ),
                            );
                        }
                        $i++;
                    }
                }
            }
            else 
            {
                $this->schemaDiff[$k]['types'] = $v;  // * Add current columns
            }

            // --------------------------------------------------------------------

            if(array_key_exists($k, $this->dbSchema)) // Sync column types
            {
                $dbTypes     = explode('|', trim($this->dbSchema[$k], '|'));
                $schemaTypes = explode('|', trim($this->fileSchema[$k], '|'));

                $diffMatches = array_diff($schemaTypes, $dbTypes);
                
                if(sizeof($diffMatches) > 0)
                {   
                    $result = preg_replace('#(\(.*?\))#','',implode('|', $diffMatches));
                    $unbreacketsDiffMatches = explode('|', $result); 

                    $i = 0;
                    $diffMatches = array_values($diffMatches);

                    foreach ($diffMatches as $diffVal)
                    {
                        if (in_array($unbreacketsDiffMatches[$i],$this->dataTypes) OR in_array($unbreacketsDiffMatches[$i], $this->attributeTypes)) // check is it datatype.
                        {
                            switch ($unbreacketsDiffMatches[$i])  
                            {
                                case '_varchar': // these types don't have brackets or contains non-numeric chars
                                case '_varbinary':

                                       preg_match('#('.$unbreacketsDiffMatches[$i].')(\(.*?\))#s',$diffMatches[$i], $match);
                                       preg_match('#(\(([0-9]*)\))#s',$diffMatches[$i], $bracketsMatch);

                                       $isBracketNull = (isset($bracketsMatch[2])) ? trim($bracketsMatch[2]) : '';
                                       if (isset($match[2]) AND ! empty($isBracketNull))
                                       {
                                           $this->schemaDiff[$k][] = array(
                                                'update_types' => $diffMatches[$i],
                                                'options' => array(
                                                    'remove-from-file',
                                                    'add-to-db'
                                                    ),
                                                );
                                       }
                                       else
                                       {
                                            $this->schemaDiff[$k]['options'] = array(
                                                'class' => 'syncerror'
                                                );
                                            $this->schemaDiff[$k][] = array(
                                                'update_types' => $diffMatches[$i],
                                                'options' => array(
                                                    'remove-from-file',
                                                    ),
                                                );
                                        }
                                        
                                    break;

                                case '_enum': // these types don't have brackets or contains non-numeric chars
                                case '_set':

                                       preg_match('#('.$unbreacketsDiffMatches[$i].')(\(.*?\))#s',$diffMatches[$i], $match);
                                       preg_match('#(\((.*?)\))#s',$diffMatches[$i], $bracketsMatch);

                                       $isBracketNull = (isset($bracketsMatch[2])) ? trim($bracketsMatch[2]) : '';
                                       if (isset($match[2]) AND ! empty($isBracketNull))
                                       {
                                           $this->schemaDiff[$k][] = array(
                                                'update_types' => $diffMatches[$i],
                                                'options' => array(
                                                    'remove-from-file',
                                                    'add-to-db'
                                                    ),
                                                );
                                       }
                                       else
                                       {
                                            $this->schemaDiff[$k]['options'] = array(
                                                'class' => 'syncerror'
                                                );
                                            $this->schemaDiff[$k][] = array(
                                                'update_types' => $diffMatches[$i],
                                                'options' => array(
                                                    'remove-from-file',
                                                    ),
                                                );
                                           }
                                    break;

                                default:
                                    
                                    $unbracketsDBColType  = preg_replace('#(\(.*?\))#s','', $this->dbSchema[$k]);
                                    $unbracketsDBColTypeArray = explode('|',$unbracketsDBColType); //if any field has a primary key 
                                    $isBracketNull = false;
                                    if ($unbreacketsDiffMatches[$i] ==  '_foreign_key') // if foreign keys field is empty 
                                    {
                                        preg_match_all('#\((.*?)\)#s',$diffMatches[$i], $match);
                                        if (count($match[1]) == 3) 
                                        {
                                            for ($j=0; $j < count($match[1]); $j++) 
                                            { 
                                                $trimed = $match[1][$j];
                                               if (empty($trimed)) 
                                               {
                                                    $isBracketNull = true;
                                                    break;
                                               }
                                            }
                                        }
                                        else
                                        {
                                            $isBracketNull = true;
                                        }
                                       
                                    }
                                    if ( ! in_array('_primary_key' ,$allDbDataArray) || $unbreacketsDiffMatches[$i] != '_primary_key' && ! $isBracketNull) // if foreign keys fields is not  empty and any other primary key doesn't exist in db 
                                    {
                                        $this->schemaDiff[$k][] = array(
                                        'update_types' => $diffMatches[$i],
                                        'options' => array(
                                            'remove-from-file',
                                            'add-to-db'
                                            ),
                                        );
                                    }
                                    else
                                    {
                                        $this->schemaDiff[$k]['options'] = array(
                                                'class' => 'syncerror'
                                                );
                                         $this->schemaDiff[$k][] = array(
                                        'update_types' => $diffMatches[$i],
                                        'options' => array(
                                            'remove-from-file',
                                            ),
                                        );
                                    }
                                    break;
                            }

                        } 
                        else 
                        {

                            $this->schemaDiff[$k]['options'] = array(
                                'class' => 'syncerror'
                                );
                            $this->schemaDiff[$k][] = array(
                            'update_types' => $diffMatches[$i], 
                            'options' => array(
                                'remove-from-file'
                                ),
                            );

                        }
                        $i++;
                    }

                }
            }

        } // end foreach

    } // end function

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
            $this->schemaObject->writeToFile($output);
        }
        
        $sync_html = new Schema_Sync\Src\Schema_Sync_Html($this, $this->schemaObject);
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


    // --------------------------------------------------------------------

    /**
     * Get number of data type count.
     * 
     * @param  string  $typeString
     * @return int
     */
    public function getDataTypeArray($typeString,$dataTypes)
    {
        return array_intersect(explode('|', $typeString),$dataTypes);
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
            $lastFunc    = $_POST['lastSyncFunc'];

            if($lastFunc == 'addKey' OR $lastFunc == 'removeKey') // add / remove attribute
            {
                $count    = count($lastCommand);
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
            $disabled   = false;
            switch ($command)
            {
                case 'drop':   // Drop Type from Database
                    
                    $colTypeArray = explode('|', $colType);
                
                    if ($isNew == true AND count($colTypeArray) > 1)  // If user want to drop this field run this process & coltype has more one
                    {
                        $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP '.$this->quoteValue($colName);
                    }
                    else // If this field exists in FileSchema
                    {
                        $unbracketsColType     = preg_replace('#(\(.*?\))#','', $colType); // Post data column types

                        $explodedColType       = explode('|',$unbracketsColType);

                        $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' ';
                        $dataTypeNotExist = false;
                        if (isset($this->fileSchema[$colName])) 
                        {
                            $unbracketsFileColType = preg_replace('#(\(.*?\))#','', $this->fileSchema[$colName]); // File schema column types
                            $explodedFileColTypes  = explode('|',$unbracketsFileColType);
                                
                                
                            if ($dbcolKeys = preg_grep('#'.$unbracketsFileColType.'#',$this->dataTypes)) // Check data type exists in FileSchema
                            {
                                $colkey            = array_search(array_values($dbcolKeys)[0],$explodedFileColTypes);// ColType array position in file schema   
                                $tempColKey        = $colkey; // temp column key for get datatype in fileschema if try to drop a previous data type
                                
                                $fileSchemaArray   = explode('|',$this->fileSchema[$colName]);
                                
                                $dataType          = $fileSchemaArray;
                                
                                $dataType[$colkey] = $this->removeUnderscore($dataType[$colkey]);// Remove Underscores from datatypes

                            }
                            else
                            {
                                $dataTypeNotExist = true;
                            }

                            if (isset($this->dbSchema[$colName])) //if this column exists in DB
                            {
                                $unbracketsDbColType = preg_replace('#(\(.*?\))#','', $this->dbSchema[$colName]); // Db schema column types
                                $explodedDbColTypes  = explode('|',$unbracketsDbColType); // Get column types from DBSchema to array 
                                
                                $dbcolKeys           = preg_grep('#'.$unbracketsDbColType.'#',$this->dataTypes);
                                $dbColKeyValue       = array_values($dbcolKeys)[0];
                                $colkey              = array_search($dbColKeyValue,$explodedDbColTypes);  // Array Position 
                                
                                $dbArray             = explode('|',$this->dbSchema[$colName]);
                                $dbArray[$colkey]    = $this->removeUnderscore($dbArray[$colkey]); // Remove Underscores from datatypes

                                if ($this->removeUnderscore($colType) == $dbArray[$colkey] && isset($dataType)) 
                                {
                                    $dbArray[$colkey] = $dataType[$tempColKey];
                                }
                                $dbArray[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Dont uppercase data inside brackets
                                    return strtoupper($match[0]); 
                                }, $dbArray[$colkey]);
                                

                                $dataType = $dbArray;
                            }
                        }
                        else
                        {
                            $unbracketsDbColType = preg_replace('#(\(.*?\))#','', $this->dbSchema[$colName]); // Db schema column types
                            $explodedDbColTypes  = explode('|',$unbracketsDbColType); // Get column types from DBSchema to array 
                            
                            $dbcolKeys           = preg_grep('#'.$unbracketsDbColType.'#',$this->dataTypes);
                            $dbColKeyValue       = array_values($dbcolKeys)[0];
                            $colkey              = array_search($dbColKeyValue,$explodedDbColTypes);  // Array Position 
                            
                            $dbArray             = explode('|',$this->dbSchema[$colName]);
                            $dataType            = $dbArray;
                            $dataType[$colkey] = $this->removeUnderscore($dataType[$colkey]); // Remove Underscores from datatypes

                        }
                        
                        if (count($dbcolKeys) > 0) // Datatype exist in schema
                        {
                            if ($colKeys = preg_grep('#'.$explodedColType[0].'#',$this->dataTypes)) // If datatype
                            {

                                $dataType[$colkey] = preg_replace_callback('#([a_-z]+(\())#', function($match) {  // Dont uppercase data inside brackets
                                    return strtoupper($match[0]); 
                                }, $dataType[$colkey]);
                                if ($isNew || $dataTypeNotExist) // if you want to drop a new field 
                                {
                                    unset($dataType[$colkey]);
                                }
                                $dbCommand .=  (isset($dataType[$colkey])) ? 'MODIFY COLUMN '.$this->quoteValue($colName).$dataType[$colkey] : 'DROP '.$this->quoteValue($colName) ;
                            }
                            else
                            {
                                switch ($explodedColType[0])    // types
                                {
                                    case '_null':

                                        $dbCommand .= 'MODIFY COLUMN '.$this->quoteValue($colName).$dataType[$colkey].' NOT NULL';
                                        break;

                                    case '_auto_increment':
                                        $dbCommand .= 'MODIFY COLUMN '.$this->quoteValue($colName).$dataType[$colkey].' NOT NULL';
                                        break;

                                    case '_unsigned':
                                        $dbCommand .= 'MODIFY COLUMN '.$this->quoteValue($colName).$dataType[$colkey];
                                        break;

                                    case '_key':    
                                    case '_unique_key' : 
                                        preg_match('#_key\((.*?)\)#',$colType,$matches);
                                        $indexName = $matches[1];
                                        $dbCommand .= 'DROP INDEX '.$this->quoteValue($indexName);
                                        break;
                                    case '_foreign_key':
                                            preg_match_all('#\((.*?)\)#',$colType,$matches);// Get Key Index Name
                                            $indexName = $matches[1][0];
                                            $dbCommand .='DROP FOREIGN KEY `'.$indexName.'`';
                                        break;
                                    case '_primary_key':
                                        $dbCommand .= 'MODIFY COLUMN '.$this->quoteValue($colName).$dataType[$colkey].',DROP PRIMARY KEY ';
                                        break;

                                    case '_not_null':
                                        $dbCommand .= 'MODIFY COLUMN '.$this->quoteValue($colName).$dataType[$colkey].' NULL';
                                        break;

                                    case '_default':
                                        $dbCommand .= 'ALTER COLUMN '.$this->quoteValue($colName).' DROP DEFAULT';
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
                    
                case 'add-to-db':   //  Add Type to Database
                    
                    $schemaKeys = explode('|',$colType);
                    
                    if (isset($this->dbSchema[$colName])) // Already exists 
                    {
                        
                        $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($colName);
                        $unbracketsColType    = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets coming from $_POST
                        $unbracketsColTypes   = explode('|',$unbracketsColType);
                        
                        $schemaKeys           = explode('|',$this->dbSchema[$colName]);
                        
                        $unbracketsDBColType  = preg_replace('#(\(.*?\))#s','', $this->dbSchema[$colName]);// Get pure DB column type withouth brackets
                        $unbracketsDBColTypes = explode('|',$unbracketsDBColType);
                        
                        $dbcolKeys            = preg_grep('#'.$unbracketsDBColType.'#',$this->dataTypes); // Data Type Confirmation
                        
                        $dbColKeyValue        = array_values($dbcolKeys)[0];
                        $colkey               = array_search($dbColKeyValue,$unbracketsDBColTypes); // Find the location of columnType  in array of dbSchema

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

                            $schemaKeys[] = $colType;

                             // get index name of KEYS 
                            $unbracketsSchemaKeys = preg_replace('#(\(.*?\))#','', $schemaKeys);// we'r using unbracketSchemaKeys to get unbracket field names  for don't lose previous changes in db  

                            for ($i=0; $i < sizeof($unbracketsSchemaKeys); $i++) // for don't lose previous changes in db  
                            { 
                                if (in_array('_primary_key',$unbracketsSchemaKeys) AND $unbracketsColType != '_primary_key') //if primary key defined in fileschema and request is not primary key

                                {
                                    $locationPK = array_search('_primary_key',$unbracketsSchemaKeys); //find location primary key in schemaArray

                                    unset($unbracketsSchemaKeys[$locationPK]); 
                                    $unbracketsSchemaKeys = array_values($unbracketsSchemaKeys);// reOrder Schema Array

                                    unset($schemaKeys[$locationPK]);
                                    $schemaKeys = array_values($schemaKeys);// reOrder Schema Array
                                }
                                switch ($unbracketsSchemaKeys[$i]) 
                                {
                                    case '_null':
                                        if ( ! isset($dbCommands[2])) 
                                        {
                                            $dbCommands[1] = strtoupper($this->removeUnderscore($schemaKeys[$i]));
                                            unset($dbCommands[3]);
                                        }
                                        break;

                                    case '_auto_increment':
                                    if (isset($dbCommands[3])) 
                                    {
                                        unset($dbCommands[3]);
                                    }
                                        $dbCommands[1] = ' NOT NULL';
                                        $dbCommands[2] = ' AUTO_INCREMENT';
                                    
                                        break;
                                    case '_unsigned':
                                        $dbCommands[0] = strtoupper($this->removeUnderscore($schemaKeys[$i]));
                                        break;
                                    case '_unique_key' : 
                                    case '_key':
                                        if ($unbracketsColType == $unbracketsSchemaKeys[$i]) // if Posted Column Type 
                                        {
                                            preg_match('#_key\((.*?)\)#',$colType,$matches);// Get Key Index Name

                                            $indexName = $matches[1];

                                            $newColType = trim($colType, '_');

                                            if (strpos($newColType, ')(') > 0) 
                                            {
                                                $newColType = trim($newColType,')');
                                                $exp     = explode(')(', $newColType);

                                                $keyIndex  = $exp[0];
                                                unset($exp[0]);

                                                $implodeKeys = array();

                                                foreach($exp as $item)
                                                {
                                                    if(strpos($item, ',') !== false)
                                                    {
                                                        foreach (explode(',', $item) as $k => $v)
                                                        {
                                                            $implodeKeys[] = $this->quoteValue($v);
                                                        }
                                                    } 
                                                    else 
                                                    {
                                                        $implodeKeys = array($this->quoteValue($item));
                                                    }
                                                }
                                                $dbCommands[5] = ',ADD'.strtoupper($this->removeUnderscore($unbracketsSchemaKeys[$i])).' '.$this->quoteValue($indexName).' ('.implode($implodeKeys, ",").')';
                                            }
                                        }
                                        break;
                                    case '_foreign_key':  
                                            preg_match_all('#\((.*?)\)#',$colType,$matches);// Get Key Index Name
                                            
                                            $indexName = $matches[1][1];
                                            $newColType = trim($colType, '_');

                                            if (strpos($colType, ')(') > 0) 
                                            {
                                                $newColType = trim($newColType,')');
                                                $exp     = explode(')(', $newColType);

                                                $keyIndex  = $exp[0];
                                                unset($exp[0]);
                                                $refField = array_values($exp);
                                                if (isset($refField[1])) 
                                                {
                                                    $dbCommands[5] = ',ADD CONSTRAINT `'.$matches[1][0].'`'.strtoupper($this->removeUnderscore($unbracketsSchemaKeys[$i])).' ('.$this->quoteValue($colName).') REFERENCES '.$this->quoteValue($indexName).' ('.$this->quoteValue($refField[1]).') ';
                                                }
                                            }
                                            break;
                                    case '_primary_key':
                                        preg_match('#(\(.*?\))#',$schemaKeys[$i], $pKColumns);
                                            
                                        $pkey = (isset($pKColumns[0])) ? $pKColumns[0] : '('.$this->quoteValue($colName).')'; 
                                        $dbCommands[4] = ',ADD PRIMARY KEY '.$pkey.' ';
                                        break;

                                    case '_not_null':
                                        $dbCommands[1] = strtoupper($this->removeUnderscore($schemaKeys[$i]));
                                        break;

                                    case '_default':
                                        if ( ! isset($dbCommands[2])) 
                                        {
                                            $defValues = ($unbracketsColType != $unbracketsSchemaKeys[$i]) ? $schemaKeys[$i] : $colType ; // if schema key and column type
                                            
                                            preg_match('#\((([^\]])*)\)#',$defValues, $defaultValue);
                                            $dbCommands[1] = ' NOT NULL';

                                            if(is_numeric($defaultValue[1])) // quote support
                                            {
                                                $dbCommands[3] = ' DEFAULT '.$defaultValue[1];
                                            } 
                                            else 
                                            {
                                                $defaultStringValue = trim($defaultValue[1], '"');
                                                $dbCommands[3] = ' DEFAULT '."'".addslashes($defaultStringValue)."'";
                                            }
                                        }   
                                        break;
                                    default :
                                        $dbCommand .= $schemaKeys[$i];
                                        break;
                                }
                            }
                        }
                    }
                    else    // New 
                    {
                        $unbracketsColType = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
                        $unbracketsColTypes = explode('|',$unbracketsColType);

                        if ( ! $columnType = preg_grep('#'.$unbracketsColType.'#', $this->dataTypes))
                        {
                            $dbCommand = 'You have to define a datatype in your file schema.';
                            $disabled = true;
                        }
                        else
                        {
                            $colKeyValue = array_values($columnType)[0];
                            $colkey      = array_search($colKeyValue, $unbracketsColTypes);//Get position in Array
                            
                            $columnType  = $this->removeUnderscore($schemaKeys[$colkey]);
                            
                            $dbCommand   = 'ALTER TABLE '.$this->schemaName;
                            $schemaKeys  = explode('|', $colType);
                            unset($schemaKeys[$colkey]);

                            $schemaKeys  = array_values($schemaKeys);
                            unset($unbracketsColTypes[$colkey]);

                            $unbracketsColTypes = array_values($unbracketsColTypes);
                            $dbCommands[5] = ''; // for multiple indexes we should define here

                            $i = 0;
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
                                    case '_unsigned':
                                        $dbCommands[0] = strtoupper($this->removeUnderscore($value));
                                        break;
                                    case '_key':
                                    case '_unique_key' :
                                        preg_match('#_key\((.*?)\)#',$schemaKeys[$i],$matches); // to get Index Name of Key
                                        $indexName = $matches[1];
                                        $schemaKeys[$i] = trim($schemaKeys[$i],'_'); //remove underscores from schemakeys

                                        if (strpos($schemaKeys[$i], ')(') > 0) // 
                                        {
                                            $schemaKeys[$i] = trim($schemaKeys[$i],")");
                                            $exp = explode(')(', $schemaKeys[$i]);

                                            unset($exp[0]);

                                            $implodeKeys = array();
                                            foreach($exp as $item)
                                            {
                                                if(strpos($item, ',') !== false)
                                                {
                                                    foreach (explode(',', $item) as $k => $v)
                                                    {
                                                        $implodeKeys[] = $this->quoteValue($v);
                                                    }
                                                } 
                                                else 
                                                {
                                                    $implodeKeys = array($this->quoteValue($item));
                                                }
                                            }
                                            $dbCommands[5] .= ',ADD'.strtoupper($this->removeUnderscore($value)).' '.$this->quoteValue($indexName).' ('.implode($implodeKeys, ",").')';
                                        }
                                        
                                        break;
                                    case '_foreign_key':
                                        preg_match_all('#\((.*?)\)#',$schemaKeys[$i],$matches);// Get Foreign Key Index Name

                                        $indexName = $matches[1][1];
                                        $schemaKeys[$i] = trim($schemaKeys[$i], '_');

                                        if (strpos($schemaKeys[$i], ')(') > 0) 
                                        {
                                            $schemaKeys[$i] = trim($schemaKeys[$i],')');
                                            $exp     = explode(')(', $schemaKeys[$i]);

                                            $keyIndex  = $exp[0];
                                            unset($exp[0]);
                                            $refField = array_values($exp);
                                        $dbCommands[5] = ',ADD CONSTRAINT `'.$matches[1][0].'`'.strtoupper($this->removeUnderscore($value)).' ('.$this->quoteValue($colName).') REFERENCES '.$this->quoteValue($indexName).' ('.$this->quoteValue($refField[1]).') ';
                                        }
                                        break;
                                    case '_primary_key':
                                            preg_match('#(\(.*?\))#',$schemaKeys[$i], $pKColumns);
                                            
                                        $pkey = (isset($pKColumns[0])) ? $pKColumns[0] : '('.$this->quoteValue($colName).')'; 
                                        $dbCommands[4] = ',ADD PRIMARY KEY '.$pkey.' ';
                                        break;

                                    case '_not_null':
                                        $dbCommands[1] = strtoupper($this->removeUnderscore($value));
                                        break;

                                    case '_default':
                                        $schemaKeys[$key] = $schemaKeys[$key];

                                        preg_match('#\((([^\]])*)\)#',$schemaKeys[$key],$matches);

                                        $dbCommands[1] = ' NOT NULL';
                                        $dbCommands[3] = ' DEFAULT '.addslashes($matches[1]);
                                        break;
                                }
                                $i++;
                            }
                            $columnType = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
                                return strtoupper($match[0]); 
                            }, $columnType);
                            
                            $dbCommand .= ' ADD COLUMN '.$this->quoteValue($colName).$columnType;

                        }   
                    }

                    for ($i = 0; $i < 8; $i++) 
                    { 
                        if (isset($dbCommands[$i])) 
                        {
                            $dbCommand.= $dbCommands[$i];
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

                        $unbracketsFileColType  = preg_replace('#(\(.*?\))#','', $this->fileSchema[$colName]); // Get pure column type withouth brackets
                        $unbracketsFileColTypes = explode('|',$unbracketsFileColType); // Array of unbracketsFileColType

                        if ($key = preg_grep('#'.$unbracketsFileColType.'#',$this->dataTypes)) // Search into datatypes with matches
                        {
                            $colFileKeyValue = array_values($key)[0];
                            $colkey  = array_search($colFileKeyValue,$unbracketsFileColTypes); // Find datatype location in matches 
                        }

                        $unbracketsColType  = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
                        $unbracketsColTypes = explode('|',$unbracketsColType); // Create pure column types without brackets and variables 
                        
                        switch ($unbracketsColTypes[0]) // types
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
                            case '_unsigned':
                            case '_primary_key':
                            case '_default':
                            case '_auto_increment':
                                is_numeric(($key = array_search($unbracketsColTypes[0],$unbracketsFileColTypes))) ? $schemaKeys[$key] = $colType : $schemaKeys[] = $colType; 
                                break;
                            case '_key':
                            case '_foreign_key':
                            case '_unique_key' : 
                                is_numeric(($key = array_search($unbracketsColTypes[0],$unbracketsFileColTypes))) ? $schemaKeys[] = $colType
                                 : $schemaKeys[] = $colType;
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

                case 'rename':

                    $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' CHANGE '.$this->quoteValue($colName).' '.$this->quoteValue('$NEW NAME');
                    $dbCommands[5] = ''; // for multiple indexes we should define here
                    $schemaKeys = explode('|',$colType);
                    $unbracketsColType  = preg_replace('#(\(.*?\))#','', $colType);// Get pure column type withouth brackets
                    $unbracketsColTypes = explode('|',$unbracketsColType); // Create pure column types without brackets and variables 
                    if ( ! $columnType = preg_grep('#'.$unbracketsColType.'#', $this->dataTypes))
                    {
                        $dbCommand = 'You have to define a datatype in your file schema.';
                        $disabled = true;
                    }
                    else
                    {
                        $colKeyValue = array_values($columnType)[0];
                        $colkey      = array_search($colKeyValue, $unbracketsColTypes);//Get position in Array
                        
                        $columnType  = $this->removeUnderscore($schemaKeys[$colkey]);
                        $i = 0;
                        
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
                                case '_unsigned':
                                    $dbCommands[0] = strtoupper($this->removeUnderscore($value));
                                    break;
                                case '_not_null':
                                    $dbCommands[1] = strtoupper($this->removeUnderscore($value));
                                    break;

                                case '_default':
                                    $schemaKeys[$key] = $this->removeUnderscore($schemaKeys[$key]);

                                    preg_match('#\((([^\]])*)\)#',$schemaKeys[$key],$matches);

                                    $dbCommands[1] = ' NOT NULL';
                                    $dbCommands[3] = ' DEFAULT '.addslashes($matches[1]);
                                    break;
                            }
                            $i++;
                        }
                    }
                        $columnType = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
                            return strtoupper($match[0]); 
                        }, $columnType);
                        $dbCommand .= $columnType;
                        for ($i = 0; $i < 7; $i++) 
                        { 
                            if (isset($dbCommands[$i])) 
                            {
                                $dbCommand.= $dbCommands[$i];
                            }
                        }

                    echo $this->schemaObject->displaySqlQueryForm($dbCommand, $this->queryWarning,$disabled); // Show sql query to developer to confirmation.

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


} // end class

/* End of file schema_sync.php */
/* Location: ./packages/schema_mysql/releases/0.0.1/src/schema_sync.php */