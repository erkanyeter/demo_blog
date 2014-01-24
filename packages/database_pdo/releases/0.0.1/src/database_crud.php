<?php
namespace Database_Pdo\Src;

/**
 * CRUD ( CREATE - READ - UPDATE - DELETE ) Class for ** PDO.
 *
 * @package       packages
 * @subpackage    database_pdo\src   
 * @category      database active record
 * @link            
 */
 
Class Database_Crud {
                                         
    public $ar_select              = array();
    public $ar_distinct            = false;
    public $ar_from                = array();
    public $ar_join                = array();
    public $ar_where               = array();
    public $ar_like                = array();
    public $ar_groupby             = array();
    public $ar_having              = array();
    public $ar_limit               = false;
    public $ar_offset              = false;
    public $ar_order               = false;
    public $ar_orderby             = array();
    public $ar_set                 = array();    
    public $ar_wherein             = array();
    public $ar_aliased_tables      = array();
    public $ar_store_array         = array();
    
    /** Caching variables **/

    public $ar_caching             = false;
    public $ar_cache_exists        = array();
    public $ar_cache_select        = array();
    public $ar_cache_from          = array();
    public $ar_cache_join          = array();
    public $ar_cache_where         = array();
    public $ar_cache_like          = array();
    public $ar_cache_groupby       = array();
    public $ar_cache_having        = array();
    public $ar_cache_orderby       = array();
    public $ar_cache_set           = array();    
    
    /**  Private variables **/
    
    public $_protect_identifiers    = true;
    public $_reserved_identifiers   = array('*'); // Identifiers that should NOT be escaped
    
    /**
    * Store $this->_compileSelect();
    * result into $sql var
    * 
    * @var string
    */
    public $sql;
    
    /**
    * This is used in _like function
    * we need to know whether like is
    * bind value (:like)
    * 
    * @var mixed
    */
    public $is_like_bind = false;

    // --------------------------------------------------------------------

    /**
     * Call CRUD Methods
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        global $packages;

        if( ! function_exists('Database_Pdo\Src\Crud\\'.$method))
        {
            require PACKAGES .'database_pdo'. DS .'releases'. DS .$packages['dependencies']['database_pdo']['version']. DS .'src'. DS .'crud'. DS .mb_strtolower($method). EXT;
        }

        return call_user_func_array('Database_Pdo\Src\Crud\\'.$method, $arguments);
    }
    
    // --------------------------------------------------------------------

    /**
    * Compile the SELECT statement
    *
    * Generates a query string based on which functions were used.
    * Should not be called directly.  The get() function calls it.
    *
    * @access    private
    * @return    string
    */
    public function _compileSelect($select_override = false)
    {
        $this->_mergeCache();   // Combine any cached components with the current statements

        // ----------------------------------------------------------------

        if ($select_override !== false)  // Write the "select" portion of the query
        {
            $sql = $select_override;
        }
        else
        {
            $sql = ( ! $this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';
        
            if (count($this->ar_select) == 0)
            {
                $sql .= '*';        
            }
            else
            {                
                // Cycle through the "select" portion of the query and prep each column name.
                // The reason we protect identifiers here rather then in the select() function
                // is because until the user calls the from() function we don't know if there are aliases
                foreach ($this->ar_select as $key => $val)
                {
                    $this->ar_select[$key] = $this->_protectIdentifiers($val);
                }
                
                $sql .= implode(', ', $this->ar_select);
            }
        }

        // ----------------------------------------------------------------
        
        // Write the "FROM" portion of the query

        if (count($this->ar_from) > 0)
        {
            $sql .= "\nFROM ";

            $sql .= $this->_fromTables($this->ar_from);
        }

        // ----------------------------------------------------------------
        
        // Write the "JOIN" portion of the query

        if (count($this->ar_join) > 0)
        {
            $sql .= "\n";

            $sql .= implode("\n", $this->ar_join);
        }

        // ----------------------------------------------------------------
        
        // Write the "WHERE" portion of the query

        if (count($this->ar_where) > 0 OR count($this->ar_like) > 0)
        {
            $sql .= "\n";

            $sql .= "WHERE ";
        }

        $sql .= implode("\n", $this->ar_where);

        // ----------------------------------------------------------------
        
        // Write the "LIKE" portion of the query
    
        if (count($this->ar_like) > 0)
        {
            if (count($this->ar_where) > 0)
            {
                $sql .= "\nAND ";
            }

            $sql .= implode("\n", $this->ar_like);
        }

        // ----------------------------------------------------------------
                             
        // Write the "GROUP BY" portion of the query
    
        if (count($this->ar_groupby) > 0)
        {
            $sql .= "\nGROUP BY ";
            
            $sql .= implode(', ', $this->ar_groupby);
        }

        // ----------------------------------------------------------------
        
        // Write the "HAVING" portion of the query
        
        if (count($this->ar_having) > 0)
        {
            $sql .= "\nHAVING ";
            $sql .= implode("\n", $this->ar_having);
        }

        // ----------------------------------------------------------------
        
        // Write the "ORDER BY" portion of the query

        if (count($this->ar_orderby) > 0)
        {
            $sql .= "\nORDER BY ";
            $sql .= implode(', ', $this->ar_orderby);
            
            if ($this->ar_order !== false)
            {
                $sql .= ($this->ar_order == 'desc') ? ' DESC' : ' ASC';
            }        
        }

        // ----------------------------------------------------------------
        
        // Write the "LIMIT" portion of the query
        
        if (is_numeric($this->ar_limit))
        {
            $sql .= "\n";
            $sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
        }

        return $sql;
    }

    // --------------------------------------------------------------------
    // 
    /**
    * Merge Cache
    *
    * When called, this function merges any cached AR arrays with 
    * locally called ones.
    *
    * @access    private
    * @return    void
    */
    function _mergeCache()
    {
        if (count($this->ar_cache_exists) == 0)
        {
            return;   
        }

        foreach ($this->ar_cache_exists as $val)
        {
            $ar_variable    = 'ar_'.$val;
            $ar_cache_var   = 'ar_cache_'.$val;

            if (count($this->$ar_cache_var) == 0)
            {
                continue;   
            }
    
            $this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
        }
    }

    // --------------------------------------------------------------------
        
    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access   private
    * @param    array    An array of fields to reset
    * @return   void
    */
    public function _resetRun($ar_reset_items)
    {
        foreach ($ar_reset_items as $item => $default_value)
        {
            if ( ! in_array($item, $this->ar_store_array))
            {
                $this->$item = $default_value;
            }

        }
    }
 
   // --------------------------------------------------------------------

    /**
    * Resets the active record values.  Called by the get() function
    *
    * @access    private
    * @return    void
    */
    public function _resetSelect()
    {
        $ar_reset_items = array(
                                'ar_select'         => array(), 
                                'ar_from'           => array(), 
                                'ar_join'           => array(), 
                                'ar_where'          => array(), 
                                'ar_like'           => array(), 
                                'ar_groupby'        => array(), 
                                'ar_having'         => array(), 
                                'ar_orderby'        => array(), 
                                'ar_wherein'        => array(), 
                                'ar_aliased_tables' => array(),
                                'ar_distinct'       => false, 
                                'ar_limit'          => false, 
                                'ar_offset'         => false, 
                                'ar_order'          => false,
                            );
        
        $this->_resetRun($ar_reset_items);
    }
    
    // --------------------------------------------------------------------
    
    /**
    * Resets the active record "write" values.
    *
    * Called by the insert() update() and delete() functions
    *
    * @access    private
    * @return    void
    */
    public function _resetWrite()
    {    
        $ar_reset_items = array(
                                'ar_set'        => array(), 
                                'ar_from'       => array(), 
                                'ar_where'      => array(), 
                                'ar_like'       => array(),
                                'ar_orderby'    => array(), 
                                'ar_limit'      => false, 
                                'ar_order'      => false,
                                'prepare'       => false
                                );

        $this->_resetRun($ar_reset_items);
    }
    
    // --------------------------------------------------------------------

    /**
    * Protect Identifiers
    *
    * This function adds backticks if appropriate based on db type
    *
    * @access   private
    * @param    mixed    the item to escape
    * @return   mixed    the item with backticks
    */
    public function protectIdentifiers($item, $prefix_single = false)
    {
        return $this->_protectIdentifiers($item, $prefix_single);
    }

    // --------------------------------------------------------------------

    /**
    * Protect Identifiers
    *
    * This function is used extensively by the Active Record class, and by
    * a couple functions in this class.
    * It takes a column or table name (optionally with an alias) and inserts
    * the table prefix onto it.  Some logic is necessary in order to deal with
    * column names that include the path.  Consider a query like this:
    *
    * SELECT * FROM hostname.database.table.column AS c FROM hostname.database.table
    *
    * Or a query with aliasing:
    *
    * SELECT m.member_id, m.member_name FROM members AS m
    *
    * Since the column name can include up to four segments (host, DB, table, column)
    * or also have an alias prefix, we need to do a bit of work to figure this out and
    * insert the table prefix (if it exists) in the proper position, and escape only
    * the correct identifiers.
    *
    * @access   private
    * @param    string
    * @param    bool
    * @param    mixed
    * @param    bool
    * @return   string
    */
    public function _protectIdentifiers($item, $prefix_single = false, $protect_identifiers = null, $field_exists = true)
    {
        if ( ! is_bool($protect_identifiers))
        {
            $protect_identifiers = $this->_protect_identifiers;
        }

        if (is_array($item))
        {
            $escaped_array = array();

            foreach($item as $k => $v)
            {
                $escaped_array[$this->_protectIdentifiers($k)] = $this->_protectIdentifiers($v);
            }

            return $escaped_array;
        }

        // Convert tabs or multiple spaces into single spaces
        $item = preg_replace('/[\t ]+/', ' ', $item);

        // If the item has an alias declaration we remove it and set it aside.
        // Basically we remove everything to the right of the first space
        $alias = '';
        if (strpos($item, ' ') !== false)
        {
            $alias = strstr($item, " ");
            $item  = substr($item, 0, - strlen($alias));
        }

        // This is basically a bug fix for queries that use MAX, MIN, etc.
        // If a parenthesis is found we know that we do not need to
        // escape the data or add a prefix.  There's probably a more graceful
        // way to deal with this, but I'm not thinking of it -- Rick
        if (strpos($item, '(') !== false)
        {
            return $item.$alias;
        }

        // Break the string apart if it contains periods, then insert the table prefix
        // in the correct location, assuming the period doesn't indicate that we're dealing
        // with an alias. While we're at it, we will escape the components
        if (strpos($item, '.') !== false)
        {
            $parts = explode('.', $item);

            // Does the first segment of the exploded item match
            // one of the aliases previously identified?  If so,
            // we have nothing more to do other than escape the item
            if (in_array($parts[0], $this->ar_aliased_tables))
            {
                if ($protect_identifiers === true)
                {
                    foreach ($parts as $key => $val)
                    {
                        if ( ! in_array($val, $this->_reserved_identifiers))
                        {
                            $parts[$key] = $this->_escapeIdentifiers($val);
                        }
                    }

                    $item = implode('.', $parts);
                }
                return $item.$alias;
            }

            // Is there a table prefix defined in the config file?  If not, no need to do anything
            if ($this->prefix != '')
            {
                // We now add the table prefix based on some logic.
                // Do we have 4 segments (hostname.database.table.column)?
                // If so, we add the table prefix to the column name in the 3rd segment.
                if (isset($parts[3]))
                {
                    $i = 2;
                }
                // Do we have 3 segments (database.table.column)?
                // If so, we add the table prefix to the column name in 2nd position
                elseif (isset($parts[2]))
                {
                    $i = 1;
                }
                // Do we have 2 segments (table.column)?
                // If so, we add the table prefix to the column name in 1st segment
                else
                {
                    $i = 0;
                }

                // This flag is set when the supplied $item does not contain a field name.
                // This can happen when this function is being called from a JOIN.
                if ($field_exists == false)
                {
                    $i++;
                }
                
                // We only add the table prefix if it does not already exist
                if (substr($parts[$i], 0, strlen($this->prefix)) != $this->prefix)
                {
                    $parts[$i] = $this->prefix.$parts[$i];
                }

                // Put the parts back together
                $item = implode('.', $parts);
            }

            if ($protect_identifiers === true)
            {
                $item = $this->_escapeIdentifiers($item);
            }

            return $item.$alias;
        }

        // Is there a table prefix?  If not, no need to insert it
        if ($this->prefix != '')
        {
            // Do we prefix an item with no segments?
            if ($prefix_single == true AND substr($item, 0, strlen($this->prefix)) != $this->prefix)
            {
                $item = $this->prefix.$item;
            }
        }

        if ($protect_identifiers === true AND ! in_array($item, $this->_reserved_identifiers))
        {
            $item = $this->_escapeIdentifiers($item);
        }

        return $item.$alias;
    }
                                  
}

/* End of file database_crud.php */
/* Location: ./packages/database_pdo/releases/0.0.1/src/database_crud.php */