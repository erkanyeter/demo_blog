<?php


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Schema_Builder_Mysql extends Schema_Builder
{

    public $dbCommand = '';

    private $schemaName;

    private $columnName;

    public $dataTypes  = array(        // defined mysql data types
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
        '_set'
    );

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

    private $dbCommands = array();


    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addToDb description]
     * @param [array] $unbracketsSchemaKeys [native Schema Keys]
     * @param [array] $unbracketsColType    [native Column Type]
     * @param [array] $schemaKeys           [Schema Keys with values]
     * @param [type] $colType              [Column Type with value]
     * @return array
     */
    public function addToDb($unbracketsSchemaKeys,$schemaKeys,$isNew = false,$colType,$unbracketsColType = null)
    {
        // $schemaKeys[] = $colType;

        $this->dbCommands[5] = '';
        $this->dbCommand = (! $isNew )  ? 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName) : 'ALTER TABLE '.$this->quoteValue($this->schemaName);
        if ($unbracketsColType != null) 
        {
            $loop = sizeof($unbracketsSchemaKeys);
            for ($k=0; $k < $loop ; $k++) 
            {
                if ($unbracketsSchemaKeys[$k] == '_unique_key' || $unbracketsSchemaKeys[$k] == '_key' || $unbracketsSchemaKeys[$k] == '_foreign_key') 
                {
                    unset($unbracketsSchemaKeys[$k]);
                    unset($schemaKeys[$k]);
                }
            }
            $schemaKeys[] = $colType;
            $unbracketsSchemaKeys[] = $unbracketsColType;
            $unbracketsSchemaKeys = array_values($unbracketsSchemaKeys);
            $schemaKeys = array_values($schemaKeys);
        }
        for ($i=0; $i < sizeof($unbracketsSchemaKeys); $i++) // for don't lose previous changes in db  
        { 
            if (in_array('_primary_key',$unbracketsSchemaKeys) AND $unbracketsColType != '_primary_key' AND ! $isNew) //if primary key defined in fileschema and request is not primary key
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
                    $this->addNull();
                    break;
                case '_auto_increment':
                    $this->addIncrement();
                    break;
                case '_unsigned':
                    $this->addUnsigned();
                    break;
                case '_unique_key' : 
                case '_key':
                     $this->addKey($unbracketsSchemaKeys[$i],$schemaKeys[$i]);
                    break;
                case '_foreign_key':  
                    $this->addForeignKey($unbracketsSchemaKeys[$i],$schemaKeys[$i]);
                        break;
                case '_primary_key':
                    $this->addPrimaryKey($schemaKeys[$i]);
                    break;
                case '_not_null':
                    $this->addNotNull();
                    break;

                case '_default':
                    $this->addDefault($unbracketsSchemaKeys[$i],$schemaKeys[$i],$colType);
                
                    break;
                default :
                    $this->addDataType($schemaKeys[$i],$isNew);
                    break;
            }
        }
        ksort($this->dbCommands);
        $this->dbCommands = array_values($this->dbCommands);
        for ($i = 0; $i < sizeof($this->dbCommands); $i++) 
        { 
            $this->dbCommand.= $this->dbCommands[$i];
        }
        return $this->dbCommand;
    } 

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Description
    * @return type
    */
    public function addToDbColumn()
    {

    }

   //-------------------------------------------------------------------------------------------------------------------------

   /**
    * [addNull description]
    */
    public function addNull()
    {
        if ( ! isset($this->dbCommands[2])) //Auto increment UNSET
        {
            $this->dbCommands[1] = 'NULL ';
            unset($this->dbCommands[3]); 
        }

    }  
     //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addDataType description]
     * @param [type]  $schemaKey [description]
     * @param boolean $isNew     [description]
     */
    public function addDataType($schemaKey,$isNew = false)
    {
         if (strpos($schemaKey,'(')) 
        {
            $schemaKey = preg_replace_callback('#([a_-z]+(\())#', function($match) { 
                return strtoupper($this->removeUnderscore($match[0])); 
            }, $schemaKey);
        }
        else
        {
            $schemaKey = strtoupper($this->removeUnderscore($schemaKey));
        }
        if ( ! $isNew) 
        {
            $this->dbCommand .= $schemaKey.' ';
        }
        else
        {
            $this->dbCommand .= ' ADD COLUMN '.$this->quoteValue($this->columnName).$schemaKey.' ';
        }
        
    }  

   //-------------------------------------------------------------------------------------------------------------------------

   /**
    * [addNotNull Create a Sql Query]
    */
    public function addNotNull()
    {
        $this->dbCommands[1] = 'NOT NULL ';
    } 

    //-------------------------------------------------------------------------------------------------------------------------

   /**
    * [addUnsigned Create a Sql Query]
    */
    public function addUnsigned()
    {
         $this->dbCommands[0] = 'UNSIGNED ';
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addDefault description]
     * @param string $unbracketSchemaKey [description]
     * @param string $schemaKey          [description]
     * @param string $colType            [description]
     */
    public function addDefault($unbracketSchemaKey,$schemaKey,$colType)
    {
        if ( ! isset($this->dbCommands[2])) 
            {
                $defValues = ($unbracketSchemaKey == '_default') ? $schemaKey : $colType ; // if schema key and column type
                preg_match('#\((([^\]])*)\)#',$defValues, $defaultValue);
                $this->dbCommands[1] = 'NOT NULL ';

                if(is_numeric($defaultValue[1])) // quote support
                {
                    $this->dbCommands[3] = 'DEFAULT '.$defaultValue[1];
                } 
                else 
                {
                    $defaultStringValue = trim($defaultValue[1], '"');
                    $this->dbCommands[3] = 'DEFAULT '."'".addslashes($defaultStringValue)."'";
                }
            }   
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addKey add Unique Key and Key]
     * @param [string] $unbracketsSchemaKey [description]
     * @param [string] $colType             [description]
     */
    public function addKey($unbracketsSchemaKey,$colType)
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
            $this->dbCommands[5] .= ',ADD'.strtoupper($this->removeUnderscore($unbracketsSchemaKey)).' '.$this->quoteValue($indexName).' ('.implode($implodeKeys, ",").')';
        }
        
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addForeignKey description]
     * @param [string] $unbracketsSchemaKey [Native Key _foreign_key]
     * @param [string] $schemaKey           [Key with value _foreign_key(KeyName)(referencesTable)(ReferenceField)]
     */
    public function addForeignKey($unbracketsSchemaKey,$schemaKey)
    {
        preg_match_all('#\((.*?)\)#',$schemaKey,$matches);// Get Key Index Name

        $indexName = $matches[1][1];
        $newColType = trim($schemaKey, '_');

        if (strpos($schemaKey, ')(') > 0) 
        {
            $newColType = trim($newColType,')');
            $exp     = explode(')(', $newColType);
            unset($exp[0]);
            
            $refField = array_values($exp);
            if (isset($refField[1])) 
            {
                $this->dbCommands[5] .= ',ADD CONSTRAINT `'.$matches[1][0].'`'.strtoupper($this->removeUnderscore($unbracketsSchemaKey)).' ('.$this->quoteValue($this->columnName).') REFERENCES '.$this->quoteValue($indexName).' ('.$this->quoteValue($refField[1]).') ';
            }
        }
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addIncrement description]
     */
    public function addIncrement()
    {
        if (isset($this->dbCommands[3])) 
        {
            unset($this->dbCommands[3]);
        }
            $this->dbCommands[1] = 'NOT NULL ';
            $this->dbCommands[2] = 'AUTO_INCREMENT ';
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [addPrimaryKey ]
     * @param [string] $schemaKey [description]
     */
    public function addPrimaryKey($schemaKey)
    {
        preg_match('#(\(.*?\))#',$schemaKey, $pKColumns);
        $pkey = (isset($pKColumns[0])) ? $pKColumns[0] : '('.$this->quoteValue($this->columnName).')'; 
        $this->dbCommands[4] = ',ADD PRIMARY KEY '.$pkey.' ';
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [changeDataTye desc]
     * @param  string $newDataType new Data Type
     * @return string Sql Query for change data types
     */
    public function changeDataType($newDataType)
    {
        return 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$newDataType;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Drop Column
    * @return string
    */
    public function dropColumn()
    {
        return 'ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP '.$this->quoteValue($this->columnName);
    }  

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Changing old column type with new one  
    * @param string $newColType 
    * @return string
    */
    public function modifyColumn ($newColType)
    {
        return 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$newColType;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * [dropAttribute creates sql query for drop attribute]
     * @param  [string] $attributeType [native Column Type]
     * @param  [string] $colType       [Column Type]
     * @param  [string] $dataType      [Data Type]
     * @return [string]                [Sql Query for drop attribute]
     */
    public function dropAttribute($attributeType,$colType,$dataType)
    {
        switch ($attributeType)    // types
        {
            case '_null':
            case '_auto_increment':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType.' NOT NULL';
                break;
            case '_unsigned':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType;
                break;

            case '_key':    
            case '_unique_key' : 
                preg_match('#_key\((.*?)\)#',$colType,$matches);
                $indexName = $matches[1];
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP INDEX '.$this->quoteValue($indexName);
                break;
            case '_foreign_key':
                    preg_match_all('#\((.*?)\)#',$colType,$matches);// Get Key Index Name
                    $indexName = $matches[1][0];
                    $dbCommand ='ALTER TABLE '.$this->quoteValue($this->schemaName).' DROP FOREIGN KEY `'.$indexName.'`';
                break;
            case '_primary_key':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType.',DROP PRIMARY KEY ';
                break;

            case '_not_null':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' MODIFY COLUMN '.$this->quoteValue($this->columnName).$dataType.' NULL';
                break;

            case '_default':
                $dbCommand = 'ALTER TABLE '.$this->quoteValue($this->schemaName).' ALTER COLUMN '.$this->quoteValue($this->columnName).' DROP DEFAULT';
                break;
        }
        return $dbCommand;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Set schemaName
    * @param type $schemaName 
    */
    public function setSchemaName($schemaName)
    {
        $this->schemaName = $schemaName;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
    * Set columnName
    * @param type $columnName 
    */
    public function setColName($columnName)
    {
        $this->columnName = $columnName;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * Get Schema Name 
     * @return string Schema Name
     */
    public function getSchemaName()
    {
        return $this->schemaName;
    }

    //-------------------------------------------------------------------------------------------------------------------------

    /**
     * Get Column Name 
     * @return string Column Name
     */
    public function getColName()
    {
        return $this->columnName;
    }

} // END class Schema_Builder_Class 