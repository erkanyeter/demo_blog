<?php


/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
Abstract class Schema_Builder
{
    protected $_escape_char    = '`%s`';

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
    * [dropAttribute creates sql query for drop attribute]
    * @param  [string] $attributeType [native Column Type]
    * @param  [string] $colType       [Column Type]
    * @param  [string] $dataType      [Data Type]
    * @return [string]                [Sql Query for drop attribute]
    */
    abstract function dropAttribute($attributeType,$colType,$dataType);

    // --------------------------------------------------------------------

    /**
    * Drop Column
    * @return string
    */
    abstract function dropColumn();

    // --------------------------------------------------------------------

    /**
    * Changing old column type with new one  
    * @param string $newColType 
    * @return string
    */
    abstract function modifyColumn($newColType);

    // --------------------------------------------------------------------
    /**
    * [dropAttribute creates sql query for drop attribute]
    * @param  [string] $attributeType [native Column Types]
    * @param  [string] $colType       [Column Type]
    * @param  [string] $dataType      [Data Type]
    * @return [string]                [Sql Query for drop attribute]
    */
    abstract function renameColumn($attributeTypes,$colType,$dataType);

    // --------------------------------------------------------------------

    /**
    * Description
    * @param type $columnType 
    * @param type $fileSchema 
    * @return type
    */
    abstract function addToFile($columnType,$fileSchema);

    // --------------------------------------------------------------------

    // /**
    //  * Description
    //  * @return type
    //  */
    abstract function setSchemaName($schemaName);

    // --------------------------------------------------------------------

    /**
    * Description
    * @return type
    */
    abstract function setColName($columnName);
   
}



// END abstract class Schema_Builder_Adapter

