<?php

/**
 * IBM DB2 Database Adapter Class
 *
 * @package       packages
 * @subpackage    pdo_ibm
 * @category      database
 * @author        Drew Harvey
 * @link                              
 */

Class Pdo_Ibm extends Pdo_Adapter
{
    /**
    * The character used for escaping
    * 
    * @var string
    */
    public $_escape_char = '';
    
    
    // clause and character used for LIKE escape sequences - not used in MySQL
    // http://publib.boulder.ibm.com/infocenter/dzichelp/v2r2/index.jsp?topic=/com.ibm.db29.doc.odbc/db2z_likesc.htm
    // same as ODBC
    public $_like_escape_str = " {escape '%s'} ";
    public $_like_escape_chr = '!';     
     
    public function __construct($param)
    {   
        parent::__construct($param);
    }
    
    /**
    * Connect to PDO
    * 
    * @author   Ersin Guvenc 
    * @param    string $dsn  Dsn
    * @param    string $user Db username
    * @param    mixed  $pass Db password
    * @param    array  $options Db Driver options
    * @link     http://www.php.net/manual/en/ref.pdo-dblib.connection.php
    * @return   void
    */
    public function connect()
    {
        // If connection is ok .. not need to again connect..
        if ($this->_conn) { return; }
        
        $port = empty($this->dbh_port) ? '' : 'PORT='.$this->dbh_port.';';
        $dsn  = empty($this->dsn) ? 'ibm:DRIVER={IBM DB2 ODBC DRIVER};DATABASE='.$this->database.';HOSTNAME='.$this->hostname.';'.$port.'PROTOCOL=TCPIP;' : $this->dsn; 
        
        $this->_pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);
        
        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    } 

    // --------------------------------------------------------------------
    
    /**
     * Escape the SQL Identifiers
     *
     * This function escapes column and table names
     *
     * @access    private
     * @param    string
     * @return    string
     */
    public function _escapeIdentifiers($item)
    {
        if ($this->_escape_char == '')
        {
            return $item;
        }

        foreach ($this->_reserved_identifiers as $id)
        {
            if (strpos($item, '.'.$id) !== false)
            {
                $str = $this->_escape_char. str_replace('.', $this->_escape_char.'.', $item);  
                
                // remove duplicates if the user already included the escape
                return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
            }        
        }
        
        if (strpos($item, '.') !== false)
        {
            $str = $this->_escape_char.str_replace('.', $this->_escape_char.'.'.$this->_escape_char, $item).$this->_escape_char;            
        }
        else
        {
            $str = $this->_escape_char.$item.$this->_escape_char;
        }
    
        // remove duplicates if the user already included the escape
        return preg_replace('/['.$this->_escape_char.']+/', $this->_escape_char, $str);
    }
            
    // --------------------------------------------------------------------
    
    /**
     * Escape String
     *
     * @access    public
     * @param    string
     * @param    bool    whether or not the string will be used in a LIKE condition
     * @return    string
     */
    public function escapeStr($str, $like = false, $side = 'both')
    {
        if (is_array($str))
        {
            foreach($str as $key => $val)
            {
                $str[$key] = $this->escapeStr($val, $like);
            }
           
           return $str;
        }

        $str = removeInvisibleCharacters($str);
        
        // escape LIKE condition wildcards
        if ($like === true)
        {
            $str = str_replace( array('%', '_', $this->_like_escape_chr),
                                array($this->_like_escape_chr.'%', $this->_like_escape_chr.'_', 
                                $this->_like_escape_chr.$this->_like_escape_chr), $str);
            switch ($side)
            {
               case 'before':
                 $str = "%{$str}";
                 break;
                 
               case 'after':
                 $str = "{$str}%";
                 break;
                 
               default:
                 $str = "%{$str}%";
            }
        } 
        
        // make sure is it bind value, if not ...
        if($this->prepare === true)
        {
            if(strpos($str, ':') === false)
            {
                $str = $this->quote($str, PDO::PARAM_STR);
            }
        }
        else
        {
           $str = $this->quote($str, PDO::PARAM_STR);
        }
        
        return $str;
    }

    /**
    * Platform specific pdo quote
    * function.
    *                 
    * @param   string $str
    * @param   int    $type
    * @return
    */
    public function quote($str, $type = null)
    {
         return $this->_conn->quote($str, $type);  
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Escape Table Name
     *
     * This function adds backticks if the table name has a period
     * in it. Some DBs will get cranky unless periods are escaped
     *
     * @access   private
     * @param    string    the table name
     * @return   string
     */
    public function _escapeTable($table)
    {
        if (stristr($table, '.'))
        {
            $table = preg_replace("/\./", "`.`", $table);
        }

        return $table;
    }
    
    /**
     * From Tables
     *
     * This function implicitly groups FROM tables so there is no confusion
     * about operator precedence in harmony with SQL standards
     *
     * @access   public
     * @param    type
     * @return   type
     */
    public function _fromTables($tables)
    {
        if ( ! is_array($tables))
        {
            $tables = array($tables);
        }

        return ' '.implode(', ', $tables).' ';
    }

    // --------------------------------------------------------------------
    
    /**
     * Insert statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @access    public
     * @param    string    the table name
     * @param    array    the insert keys
     * @param    array    the insert values
     * @return    string
     */
    public function _insert($table, $keys, $values)
    {
        return "INSERT INTO " . $this->_escapeTable($table) . " (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
    }
    
    // --------------------------------------------------------------------
    
    
    /**
     * Delete statement
     *
     * Generates a platform-specific delete string from the supplied data
     *
     * @access   public
     * @param    string    the table name
     * @param    array    the where clause
     * @return   string
     */
    public function _delete($table, $where = array(), $like = array(), $limit = false)
    {
        return "DELETE FROM ".$this->_escapeTable($table)." WHERE ".implode(" ", $where);
    }
    
    // --------------------------------------------------------------------
    
    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @access   public
     * @param    string    the table name
     * @param    array    the update data
     * @param    array    the where clause
     * @return   string
     */
    public function _update($table, $values, $where, $orderby = array(), $limit = false)
    {
        foreach($values as $key => $val)
        {
            $valstr[] = $key." = ".$val;
        }

        return "UPDATE ".$this->_escapeTable($table)." SET ".implode(', ', $valstr)." WHERE ".implode(" ", $where);
    }

    // --------------------------------------------------------------------

    /**
     * Limit string
     *
     * Generates a platform-specific LIMIT clause
     *
     * @access   public
     * @param    string    the sql query string
     * @param    integer   the number of rows to limit the query to
     * @param    integer   the offset value
     * @return   string
     */
    public function _limit($sql, $limit, $offset = 0)
    {
        if ($offset == 0)
        {
            $offset = '';
        }
        else
        {
            $offset .= ", ";
        }

        return $sql."RETURN FIRST  " . $limit . " ROWS ONLY";
    }
    
    /**
    * Get Platform Specific Database 
    * Version number. From Zend.
    *
    * @access    public
    * @return    string
    */
    public function version()
    {
        try 
        {
            $stmt = $this->_conn->query('SELECT service_level, fixpack_num FROM TABLE (sysproc.env_get_inst_info()) as INSTANCEINFO');
            
            $result = $stmt->fetchAll(PDO::FETCH_NUM);
            
            if (count($result))
            {
                $matches = null;
                if (preg_match('/((?:[0-9]{1,2}\.){1,3}[0-9]{1,2})/', $result[0][0], $matches))
                {
                    return $matches[1];
                } 
                else 
                {
                    return null;
                }
            }
            return null;
            
        } catch (PDOException $e) 
        {
            return null;
        }
    }


} // end class.


/* End of file Pdo_Ibm.php */
/* Location: ./packages/pdo_ibm/releases/0.0.1/pdo_ibm.php */