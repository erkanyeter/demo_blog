<?php

namespace Obullo\Database\Crud;

use RunTimeException, Exception;

/**
 * CRUD ( CREATE - READ - UPDATE - DELETE ) Class
 * Borrowed from Codeigniter Active Record
 * 
 * @category  Database
 * @package   Crud
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/database
 */
Class Crud
{
    public $ar_select = array();
    public $ar_distinct = false;
    public $ar_from = array();
    public $ar_join = array();
    public $ar_where = array();
    public $ar_like = array();
    public $ar_groupby = array();
    public $ar_having = array();
    public $ar_limit = false;
    public $ar_offset = false;
    public $ar_order = false;
    public $ar_orderby = array();
    public $ar_set = array();
    public $ar_wherein = array();
    public $ar_aliased_tables = array();
    public $ar_store_array = array();

    /** Caching variables **/

    public $ar_caching = false;
    public $ar_cache_exists = array();
    public $ar_cache_select = array();
    public $ar_cache_from = array();
    public $ar_cache_join = array();
    public $ar_cache_where = array();
    public $ar_cache_like = array();
    public $ar_cache_groupby = array();
    public $ar_cache_having = array();
    public $ar_cache_orderby = array();
    public $ar_cache_set = array();

    /**
     * Pdo Adapter ( mysql, pgsql .. )
     * 
     * @var object
     */
    public $adapter;

    /**
     * Constructor
     * 
     * @param object $db database object
     */
    public function __construct($db)
    {
        if ( ! is_object($db)) {
            throw new RunTimeException('Crud class requires database object.');
        }
        $this->adapter = $db;  // load pdo adapter object.
    }

    /**
     * If method not exists call from adapter class
     * 
     * @param string $method    methodname
     * @param array  $arguments method arguments
     * 
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array(array($this->adapter, $method), $arguments);
    }

    /**
     * Select
     * 
     * @param string  $select fields
     * @param boolean $escape use escape or noe 
     * 
     * @return object
     */
    public function select($select = '*', $escape = null)
    {
        if (is_bool($escape)) {   // Set the global value if this was sepecified  
            $this->adapter->_protect_identifiers = $escape;
        }
        if (is_string($select)) {
            $select = explode(',', $select);
        }
        foreach ($select as $val) {
            $val = trim($val);
            if ($val != '') {
                $this->ar_select[] = $val;
                if ($this->ar_caching === true) {
                    $this->ar_cache_select[] = $val;
                    $this->ar_cache_exists[] = 'select';
                }
            }
        }
        return $this;
    }

    /**
     * Join tables
     * 
     * @param string $table tablename
     * @param string $cond  join condition
     * @param string $type  join type ( inner, outer, left, right  .. )
     * 
     * @return object
     */
    public function join($table, $cond, $type = '')
    {
        if ($type != '') {
            $type = strtoupper(trim($type));

            if ( ! in_array($type, array('LEFT', 'RIGHT', 'OUTER', 'INNER', 'LEFT OUTER', 'RIGHT OUTER'))) {
                $type = '';
            } else {
                $type.= ' ';
            }
        }
        //
        // Extract any aliases that might exist.  We use this information
        // in the _protect_identifiers to know whether to add a table prefix 
        // 
        $this->_trackAliases($table);

        if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match)) {   // Strip apart the condition and protect the identifiers
            $match[1] = $this->_protectIdentifiers($match[1]);
            $match[3] = $this->_protectIdentifiers($match[3]);

            $cond = $match[1] . $match[2] . $match[3];
        }
        $join = $type . 'JOIN ' . $this->_protectIdentifiers($table, true, null, false) . ' ON ' . $cond;  // Assemble the JOIN statement
        $this->ar_join[] = $join;

        if ($this->ar_caching === true) {
            $this->ar_cache_join[] = $join;
            $this->ar_cache_exists[] = 'join';
        }
        return $this;
    }

    /**
     * From 
     * 
     * @param array $from sql from
     * 
     * @return object
     */
    public function from($from)
    {
        foreach ((array) $from as $val) {
            if (strpos($val, ',') !== false) {
                foreach (explode(',', $val) as $v) {
                    $v = trim($v);
                    $this->_trackAliases($v);
                    $this->ar_from[] = $this->_protectIdentifiers($v, true, null, false);

                    if ($this->ar_caching === true) {
                        $this->ar_cache_from[] = $v;
                        $this->ar_cache_exists[] = 'from';
                    }
                }
            } else {

                $val = trim($val);

                // Extract any aliases that might exist.  We use this information
                // in the _protect_identifiers to know whether to add a table prefix 
                $this->_trackAliases($val);

                $this->ar_from[] = $this->_protectIdentifiers($val, true, null, false);

                if ($this->ar_caching === true) {
                    $this->ar_cache_from[] = $this->_protectIdentifiers($val, true, null, false);
                    $this->ar_cache_exists[] = 'from';
                }
            }
        } // end
        return ($this);
    }

    /**
     * Get
     *
     * Compiles the select statement based on the other functions called
     * and runs the query
     *
     * @param string  $table  the table
     * @param integer $limit  the limit clause
     * @param integer $offset the offset clause
     * 
     * @return object
     */
    public function get($table = '', $limit = null, $offset = null)
    {
        if ($table != '') {
            $this->_trackAliases($table);
            $this->from($table);
        }
        if ( ! is_null($limit)) {
            $this->limit($limit, $offset);
        }
        $this->adapter->sql = $this->_compileSelect();

        $result = $this->adapter->query($this->adapter->sql);
        $this->_resetSelect();
        return $result;
    }

    /**
     * Where clause
     * 
     * @param string  $key    key
     * @param mixed   $value  value
     * @param boolean $escape whether to false quotes
     * 
     * @return object
     */
    public function where($key, $value = null, $escape = true)
    {
        return $this->_where($key, $value, 'AND ', $escape);
    }

    /**
     * Like where but use also IN clause
     * 
     * @param string $key    key
     * @param array  $values values
     * 
     * @return object
     */
    public function whereIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values);
    }

    /**
     * Like where but use also OR clause
     * 
     * @param string  $key    key
     * @param mixed   $value  value
     * @param boolean $escape whether to false quotes
     * 
     * @return object
     */
    public function orWhere($key, $value = null, $escape = true)
    {
        return $this->_where($key, $value, 'OR ', $escape);
    }

    /**
     * Like where but use also IN clause
     * 
     * @param string $key    key
     * @param array  $values values
     * 
     * @return object
     */
    public function orWhereIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values, false, 'OR ');
    }

    /**
     * Like where but use also OR NOT IN clause
     * 
     * @param string $key    key
     * @param array  $values values
     * 
     * @return object
     */
    public function orWhereNotIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values, true, 'OR ');
    }

    /**
     * Like where but use also NOT IN clause
     * 
     * @param string $key    key
     * @param array  $values values
     * 
     * @return object
     */
    public function whereNotIn($key = null, $values = null)
    {
        return $this->_whereIn($key, $values, true);
    }

    /**
     * Where
     *
     * Called by where() or orWhere()
     *
     * @param mixed  $key    key
     * @param mixed  $value  value
     * @param string $type   clause type
     * @param string $escape whether to escape
     * 
     * @return object
     */
    public function _where($key, $value = null, $type = 'AND ', $escape = null)
    {
        if ( ! is_array($key)) {
            $key = array($key => $value);
        }
        if ( ! is_bool($escape)) {   // If the escape value was not set will base it on the global setting
            $escape = $this->adapter->_protect_identifiers;
        }
        foreach ($key as $k => $v) {
            $prefix = (sizeof($this->ar_where) == 0 AND sizeof($this->ar_cache_where) == 0) ? '' : $type;

            if (is_null($v) AND ! $this->_hasOperator($k)) {
                $k .= ' IS null';   // value appears not to have been set, assign the test to IS null 
            }
            if ( ! is_null($v)) {

                if ($escape === true) {

                    $k = $this->_protectIdentifiers($k, false, $escape);
                    $v = ' ' . $this->adapter->escape($v);

                } elseif (is_string($v)) {
                    $v = "{$v}";
                }
                if ( ! $this->_hasOperator($k)) {
                    $k .= ' =';
                }
                
            } else {
                $k = $this->_protectIdentifiers($k, false, $escape);
            }
            $this->ar_where[] = $prefix . $k . $v;

            if ($this->ar_caching === true) {
                $this->ar_cache_where[]  = $prefix . $k . $v;
                $this->ar_cache_exists[] = 'where';
            }
        }
        return $this;
    }

    /**
     * Where_in
     *
     * Called by where_in, where_in_or, where_not_in, where_not_in_or
     *
     * @param string  $key    The field to search
     * @param array   $values The values searched on
     * @param boolean $not    If the statement would be IN or NOT IN
     * @param string  $type   clause type
     * 
     * @return object
     */
    public function _whereIn($key = null, $values = null, $not = false, $type = 'AND ')
    {
        if ($key === null OR $values === null) {
            return;
        }
        if (!is_array($values)) {
            $values = array($values);
        }
        $not = ($not) ? ' NOT' : '';

        foreach ($values as $value) {
            $this->ar_wherein[] = $this->escape($value);
        }
        $prefix = (sizeof($this->ar_where) == 0) ? '' : $type;
        $where_in = $prefix . $this->_protectIdentifiers($key) . $not . " IN (" . implode(", ", $this->ar_wherein) . ") ";

        $this->ar_where[] = $where_in;
        if ($this->ar_caching === true) {
            $this->ar_cache_where[]  = $where_in;
            $this->ar_cache_exists[] = 'where';
        }
        $this->ar_wherein = array();   // reset the array for multiple calls
        return $this;
    }

    /**
     * Like clause
     * 
     * @param string $field name
     * @param string $match value
     * @param string $side  direction
     * 
     * @return object
     */
    public function like($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side);
    }

    /**
     * Like "Like" also use OR
     * 
     * @param string $field name
     * @param string $match value
     * @param string $side  direction
     * 
     * @return object
     */
    public function orLike($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side);
    }

    /**
     * Like "Like" also use NOT
     * 
     * @param string $field name
     * @param string $match value
     * @param string $side  direction
     * 
     * @return object
     */
    public function notLike($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'AND ', $side, 'NOT');
    }

    /**
     * Like "Like" also use OR NOT
     * 
     * @param string $field name
     * @param string $match value
     * @param string $side  direction
     * 
     * @return object
     */
    public function orNotLike($field, $match = '', $side = 'both')
    {
        return $this->_like($field, $match, 'OR ', $side, 'NOT');
    }

    /**
     * Sets the ORDER BY value
     *
     * @param string $orderby   fieldname(s)
     * @param string $direction ASC or DESC
     * 
     * @return object
     */
    public function orderBy($orderby, $direction = '')
    {
        $direction = strtoupper(trim($direction));
        if ($direction != '') {
            switch ($direction) {
            case 'ASC':
                $direction = ' ASC';
                break;
            case 'DESC':
                $direction = ' DESC';
                break;
            default:
                $direction = ' ASC';
            }
        }
        if (strpos($orderby, ',') !== false) {
            $temp = array();
            foreach (explode(',', $orderby) as $part) {
                $part = trim($part);
                if (!in_array($part, $this->ar_aliased_tables)) {
                    $part = $this->_protectIdentifiers(trim($part));
                }
                $temp[] = $part;
            }
            $orderby = implode(', ', $temp);
        } else {
            $orderby = $this->_protectIdentifiers($orderby);
        }
        $orderby_statement  = $orderby . $direction;
        $this->ar_orderby[] = $orderby_statement;

        if ($this->ar_caching === true) {
            $this->ar_cache_orderby[] = $orderby_statement;
            $this->ar_cache_exists[]  = 'orderby';
        }
        return $this;
    }

    /**
     * Sets the HAVING values
     *
     * Called by having() or orHaving()
     *
     * @param string $key    name
     * @param string $value  value
     * @param string $type   type
     * @param string $escape whether to escape
     * 
     * @return   object
     */
    public function _having($key, $value = '', $type = 'AND ', $escape = true)
    {
        if (!is_array($key)) {
            $key = array($key => $value);
        }
        foreach ($key as $k => $v) {
            $prefix = (sizeof($this->ar_having) == 0) ? '' : $type;
            if ($escape === true) {
                $k = $this->_protectIdentifiers($k);
            }
            if (!$this->_hasOperator($k)) {
                $k .= ' = ';
            }
            if ($v != '') {
                $v = ' ' . $this->adapter->escape($v);  // obullo changes ..
            }
            $this->ar_having[] = $prefix . $k . $v;
            if ($this->ar_caching === true) {
                $this->ar_cache_having[] = $prefix . $k . $v;
                $this->ar_cache_exists[] = 'having';
            }
        }
        return $this;
    }

    /**
     * orHaving description
     * 
     * @param string  $key    key
     * @param string  $value  value
     * @param boolean $escape whether to escape
     * 
     * @return object
     */
    public function orHaving($key, $value = '', $escape = true)
    {
        return $this->_having($key, $value, 'OR ', $escape);
    }

    /**
     * Like
     *
     * Called by like() or orLike()
     *
     * @param mixed  $field fieldname
     * @param mixed  $match match
     * @param string $type  type
     * @param string $side  direction
     * @param string $not   not clause
     * 
     * @return object
     */
    public function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
    {
        if ( ! is_array($field)) {
            $field = array($field => $match);
        }
        foreach ($field as $k => $v) {
            $k = $this->_protectIdentifiers($k);
            $prefix = (sizeof($this->ar_like) == 0) ? '' : $type;

            $like_statement = $prefix . " $k $not LIKE " . $this->adapter->escapeLike($v, $side);

            // some platforms require an escape sequence definition for LIKE wildcards
            if ($this->adapter->_like_escape_str != '') {
                $like_statement = $like_statement . sprintf($this->adapter->_like_escape_str, $this->adapter->_like_escape_chr);
            }
            $this->ar_like[] = $like_statement;
            if ($this->ar_caching === true) {
                $this->ar_cache_like[] = $like_statement;
                $this->ar_cache_exists[] = 'like';
            }
        }
        return $this;
    }

    /**
     * Limit
     * 
     * @param integer $value  limit value
     * @param integer $offset offset
     * 
     * @return object
     */
    public function limit($value, $offset = '')
    {
        $this->ar_limit = $value;
        if ($offset != '') {
            $this->ar_offset = $offset;
        }
        return $this;
    }

    /**
     * Offset
     * 
     * @param integer $offset offset
     * 
     * @return object
     */
    public function offset($offset)
    {
        $this->ar_offset = $offset;
        return $this;
    }

    /**
     * Group by
     *
     * @param string $by group by
     * 
     * @return object
     */
    public function groupBy($by)
    {
        if (is_string($by)) {
            $by = explode(',', $by);
        }
        foreach ($by as $val) {
            $val = trim($val);
            if ($val != '') {
                $this->ar_groupby[] = $this->_protectIdentifiers($val);
                if ($this->ar_caching === true) {
                    $this->ar_cache_groupby[] = $this->_protectIdentifiers($val);
                    $this->ar_cache_exists[]  = 'groupby';
                }
            }
        }
        return $this;
    }

    /**
     * Compile the SELECT statement
     *
     * Generates a query string based on which functions were used.
     * Should not be called directly.  The get() function calls it.
     *
     * @param boolean $select_override whether override
     * 
     * @return string
     */
    public function _compileSelect($select_override = false)
    {
        $this->_mergeCache();   // Combine any cached components with the current statements

        if ($select_override !== false) { // Write the "select" portion of the query
            $sql = $select_override;
        } else {
            $sql = ( ! $this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';

            if (count($this->ar_select) == 0) {
                $sql .= '*';
            } else {
                // Cycle through the "select" portion of the query and prep each column name.
                // The reason we protect identifiers here rather then in the select() function
                // is because until the user calls the from() function we don't know if there are aliases
                foreach ($this->ar_select as $key => $val) {
                    $this->ar_select[$key] = $this->_protectIdentifiers($val);
                }
                $sql .= implode(', ', $this->ar_select);
            }
        }

        // Write the "FROM" portion of the query

        if (count($this->ar_from) > 0) {
            $sql .= "\nFROM ";
            $sql .= $this->adapter->_fromTables($this->ar_from);
        }

        // Write the "JOIN" portion of the query

        if (count($this->ar_join) > 0) {
            $sql .= "\n";
            $sql .= implode("\n", $this->ar_join);
        }

        // Write the "WHERE" portion of the query

        if (count($this->ar_where) > 0 OR count($this->ar_like) > 0) {
            $sql .= "\n";
            $sql .= "WHERE ";
        }

        $sql .= implode("\n", $this->ar_where);

        // Write the "LIKE" portion of the query

        if (count($this->ar_like) > 0) {
            if (count($this->ar_where) > 0) {
                $sql .= "\nAND ";
            }
            $sql .= implode("\n", $this->ar_like);
        }

        // Write the "GROUP BY" portion of the query

        if (count($this->ar_groupby) > 0) {
            $sql .= "\nGROUP BY ";
            $sql .= implode(', ', $this->ar_groupby);
        }

        // Write the "HAVING" portion of the query

        if (count($this->ar_having) > 0) {
            $sql .= "\nHAVING ";
            $sql .= implode("\n", $this->ar_having);
        }

        // Write the "ORDER BY" portion of the query

        if (count($this->ar_orderby) > 0) {
            $sql .= "\nORDER BY ";
            $sql .= implode(', ', $this->ar_orderby);

            if ($this->ar_order !== false) {
                $sql .= ($this->ar_order == 'desc') ? ' DESC' : ' ASC';
            }
        }

        // Write the "LIMIT" portion of the query

        if (is_numeric($this->ar_limit)) {
            $sql .= "\n";
            $sql = $this->adapter->_limit($sql, $this->ar_limit, $this->ar_offset);
        }
        return $sql;
    }

    /**
     * Tests whether the string has an SQL operator
     *
     * @param string $str sql string
     * 
     * @return bool
     */
    public function _hasOperator($str)
    {
        $str = trim($str);
        if ( ! preg_match("/(\s|<|>|!|=|is null|is not null)/i", $str)) {
            return false;
        }
        return true;
    }

    /**
     * Resets the active record values.  Called by the get() function
     *
     * @param array $ar_reset_items an array of fields to reset
     * 
     * @return void
     */
    public function _resetRun($ar_reset_items)
    {
        foreach ($ar_reset_items as $item => $default_value) {
            if ( ! in_array($item, $this->ar_store_array)) {
                $this->{$item} = $default_value;
            }
        }
    }

    /**
     * Resets the active record values.  Called by the get() function
     *
     * @return    void
     */
    public function _resetSelect()
    {
        $ar_reset_items = array(
            'ar_select' => array(),
            'ar_from' => array(),
            'ar_join' => array(),
            'ar_where' => array(),
            'ar_like' => array(),
            'ar_groupby' => array(),
            'ar_having' => array(),
            'ar_orderby' => array(),
            'ar_wherein' => array(),
            'ar_aliased_tables' => array(),
            'ar_distinct' => false,
            'ar_limit' => false,
            'ar_offset' => false,
            'ar_order' => false,
        );
        $this->_resetRun($ar_reset_items);
    }

    /**
     * Resets the active record "write" values.
     *
     * Called by the insert() update() and delete() functions
     *
     * @return    void
     */
    public function _resetWrite()
    {
        $ar_reset_items = array(
            'ar_set' => array(),
            'ar_from' => array(),
            'ar_where' => array(),
            'ar_like' => array(),
            'ar_orderby' => array(),
            'ar_limit' => false,
            'ar_order' => false,
        );
        $this->_resetRun($ar_reset_items);
    }

    /**
     * "Smart" Escape String via PDO
     *
     * Escapes data based on type
     * Sets boolean and null types
     *
     * @param string $str escape str
     * 
     * @return mixed
     */
    public function escape($str)
    {
        if (is_string($str)) {
            return $this->adapter->escapeStr($str);
        }
        if (is_integer($str)) {
            return (int) $str;
        }
        if (is_double($str)) {
            return (double) $str;
        }
        if (is_float($str)) {
            return (float) $str;
        }
        if (is_bool($str)) {
            return ($str === false) ? 0 : 1;
        }
        if (is_null($str)) {
            return 'null';
        }
    }

    /**
     * Escape LIKE String
     *
     * Calls the individual driver for platform
     * specific escaping for LIKE conditions
     *
     * @param string $str  item
     * @param string $side both
     * 
     * @return mixed
     */
    public function escapeLike($str, $side = 'both')
    {
        return $this->adapter->escapeStr($str, true, $side);
    }

    /**
     * Protect Identifiers
     *
     * This function adds backticks if appropriate based on db type
     *
     * @param mixed  $item          the item to escape
     * @param boelan $prefix_single prefix single option
     * 
     * @return mixed the item with backticks
     */
    public function protectIdentifiers($item, $prefix_single = false)
    {
        return $this->_protectIdentifiers($item, $prefix_single);
    }

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
     * @param string  $item                item
     * @param boolean $prefix_single       feature
     * @param mixed   $protect_identifiers feature
     * @param bool    $field_exists        control
     * 
     * @return string
     */
    public function _protectIdentifiers($item, $prefix_single = false, $protect_identifiers = null, $field_exists = true)
    {
        if ( ! is_bool($protect_identifiers)) {
            $protect_identifiers = $this->adapter->_protect_identifiers;
        }

        if (is_array($item)) {
            $escaped_array = array();
            foreach ($item as $k => $v) {
                $escaped_array[$this->_protectIdentifiers($k)] = $this->_protectIdentifiers($v);
            }
            return $escaped_array;
        }
        $item = preg_replace('/[\t ]+/', ' ', $item); // Convert tabs or multiple spaces into single spaces

        // If the item has an alias declaration we remove it and set it aside.
        // Basically we remove everything to the right of the first space
        $alias = '';
        if (strpos($item, ' ') !== false) {
            $alias = strstr($item, " ");
            $item = substr($item, 0, - strlen($alias));
        }

        // This is basically a bug fix for queries that use MAX, MIN, etc.
        // If a parenthesis is found we know that we do not need to
        // escape the data or add a prefix.  There's probably a more graceful
        // way to deal with this, but I'm not thinking of it -- Rick
        if (strpos($item, '(') !== false) {
            return $item . $alias;
        }

        // Break the string apart if it contains periods, then insert the table prefix
        // in the correct location, assuming the period doesn't indicate that we're dealing
        // with an alias. While we're at it, we will escape the components
        if (strpos($item, '.') !== false) {
            $parts = explode('.', $item);

            // Does the first segment of the exploded item match
            // one of the aliases previously identified?  If so,
            // we have nothing more to do other than escape the item
            if (in_array($parts[0], $this->ar_aliased_tables)) {
                if ($protect_identifiers === true) {
                    foreach ($parts as $key => $val) {
                        if ( ! in_array($val, $this->adapter->_reserved_identifiers)) {
                            $parts[$key] = $this->adapter->_escapeIdentifiers($val);
                        }
                    }
                    $item = implode('.', $parts);
                }
                return $item . $alias;
            }

            // Is there a table prefix defined in the config file?  If not, no need to do anything
            if ($this->adapter->prefix != '') {
                // We now add the table prefix based on some logic.
                // Do we have 4 segments (hostname.database.table.column)?
                // If so, we add the table prefix to the column name in the 3rd segment.
                if (isset($parts[3])) {
                    $i = 2;
                } elseif (isset($parts[2])) {       // Do we have 3 segments (database.table.column)?
                    $i = 1;                         // If so, we add the table prefix to the column name in 2nd position
                } else {                  // Do we have 2 segments (table.column)?
                    $i = 0;               // If so, we add the table prefix to the column name in 1st segment
                }

                // This flag is set when the supplied $item does not contain a field name.
                // This can happen when this function is being called from a JOIN.
                if ($field_exists == false) {
                    $i++;
                }
                // We only add the table prefix if it does not already exist
                if (substr($parts[$i], 0, strlen($this->adapter->prefix)) != $this->adapter->prefix) {
                    $parts[$i] = $this->adapter->prefix . $parts[$i];
                }
                // Put the parts back together
                $item = implode('.', $parts);
            }
            if ($protect_identifiers === true) {
                $item = $this->adapter->_escapeIdentifiers($item);
            }
            return $item . $alias;
        }
        if ($this->adapter->prefix != '') {          // Is there a table prefix?  If not, no need to insert it
            // Do we prefix an item with no segments?
            if ($prefix_single == true AND substr($item, 0, strlen($this->adapter->prefix)) != $this->adapter->prefix) {
                $item = $this->adapter->prefix . $item;
            }
        }
        if ($protect_identifiers === true AND !in_array($item, $this->adapter->_reserved_identifiers)) {
            $item = $this->adapter->_escapeIdentifiers($item);
        }
        return $item . $alias;
    }

    /**
     * Track Aliases
     *
     * Used to track SQL statements written with aliased tables.
     *
     * @param string $table the table to inspect
     * 
     * @return string
     */
    private function _trackAliases($table)
    {
        if (is_array($table)) {
            foreach ($table as $t) {
                $this->_trackAliases($t);
            }
            return;
        }
        // Does the string contain a comma?  If so, we need to separate
        // the string into discreet statements
        if (strpos($table, ',') !== false) {
            return $this->_trackAliases(explode(',', $table));
        }
        // if a table alias is used we can recognize it by a space
        if (strpos($table, " ") !== false) {
            // if the alias is written with the AS keyword, remove it
            $table = preg_replace('/ AS /i', ' ', $table);

            // Grab the alias
            $table = trim(strrchr($table, " "));

            // Store the alias, if it doesn't already exist
            if (!in_array($table, $this->ar_aliased_tables)) {
                $this->ar_aliased_tables[] = $table;
            }
        }
    }

    /**
     * DISTINCT
     *
     * Sets a flag which tells the query string compiler to add DISTINCT
     *
     * @param boolean $val value
     * 
     * @return object
     */
    public function distinct($val = true)
    {
        $this->ar_distinct = (is_bool($val)) ? $val : true;
        return $this;
    }

    /**
     * Delete
     *
     * Compiles a delete string and runs the query
     *
     * @param mixed   $table      the table(s) to delete from. String or array
     * @param mixed   $where      the where clause
     * @param mixed   $limit      the limit clause
     * @param boolean $reset_data feature
     * 
     * @return object
     */
    public function delete($table = '', $where = '', $limit = null, $reset_data = true)
    {
        $this->_mergeCache();   // Combine any cached components with the current statements
        if ($table == '') {
            $table = $this->ar_from[0];
        } else {
            $table = $this->_protectIdentifiers($table, true, null, false);
        }
        if ($where != '') {
            $this->where($where);
        }
        if ($limit != null) {
            $this->limit($limit);
        }
        if (sizeof($this->ar_where) == 0 AND sizeof($this->ar_wherein) == 0 AND sizeof($this->ar_like) == 0) {
            throw new Exception('Deletes are not allowed unless they contain a "where" or "like" clause.');
        }
        $this->from($table); // set tablename for set() function.
        $sql = $this->adapter->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);

        if ($reset_data) {
            $this->_resetWrite();
        }
        return $this->adapter->exec($sql); // return number of  affected rows
    }

    /**
     * The "set" function.  Allows key / value pairs to be set for inserting or updating
     *
     * @param mixed   $key    array or string
     * @param string  $value  update string
     * @param boolean $escape whether to escape
     * 
     * @return   void
     */
    public function set($key, $value = '', $escape = true)
    {
        if ( ! is_array($key)) {
            $key = array($key => $value);
        }
        foreach ($key as $k => $v) {
            if ($escape === false) {
                if (strpos($v, ':') === false OR strpos($v, ':') > 0) { // We make sure is it bind value, if not ... 
                    if (is_string($v)) {
                        $v = "{$v}";  // PDO changes.
                    }
                }
                $this->ar_set[$this->_protectIdentifiers($k)] = $v;
            } else {
                $this->ar_set[$this->_protectIdentifiers($k)] = $this->adapter->escape($v);
            }
        }
        return $this;
    }

    /**
     * Replace
     *
     * Compiles an replace into string and runs the query
     *
     * @param string $table the table to replace data into
     * @param array  $set   an associative array of insert values
     * 
     * @return  object
     */
    public function replace($table = '', $set = null)
    {
        if ($table == '') {
            $table = $this->ar_from[0];
        } else {
            $this->from($table); // set tablename for set() function.
        }
        if ( ! is_null($set)) {
            $this->set($set);
        }
        $sql = $this->adapter->_replace($this->_protectIdentifiers($table, true, null, false), array_keys($this->ar_set), array_values($this->ar_set));
        $this->_resetWrite();
        return $this->adapter->exec($sql);
    }

    /**
     * Update
     *
     * Compiles an update string and runs the query
     *
     * @param string $table   the table to retrieve the results from
     * @param array  $set     an associative array of update values
     * @param array  $options update options
     * 
     * @return   PDO exec number of affected rows
     */
    public function update($table = '', $set = null, $options = array())
    {
        $options = array();     // Update options.
        if ($table == '') {     // Set table
            $table = $this->ar_from[0];
        } else {
            $this->from($table); // set tablename for set() function.
        }
        $this->_mergeCache();  // Combine any cached components with the current statements

        if ( ! is_null($set)) {
            $this->set($set);
        }
        $sql = $this->adapter->_update($this->_protectIdentifiers($table, true, null, false), $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);
        $this->_resetWrite();

        return $this->adapter->exec($sql);  // return number of affected rows.  
    }

    /**
     * Insert
     *
     * Compiles an insert string and runs the query
     *
     * @param string $table   the table to retrieve the results from
     * @param array  $set     an associative array of insert values
     * @param array  $options insert options
     * 
     * @return PDO exec number of affected rows.
     */
    public function insert($table = '', $set = null, $options = array())
    {
        $options = array();
        if ($table == '') {  // Set table
            $table = $this->ar_from[0];
        } else {
            $this->from($table); // set tablename correctly.
        }
        if ( ! is_null($set)) {
            $this->set($set);
        }
        $sql = $this->adapter->_insert($this->_protectIdentifiers($table, true, null, false), array_keys($this->ar_set), array_values($this->ar_set));
        $this->_resetWrite();

        return $this->adapter->exec($sql);  // return affected rows ( PDO support )
    }

    /**
     * Start Cache
     *
     * Starts AR caching
     *
     * @return    void
     */
    public function startCache()
    {
        $this->ar_caching = true;
    }

    /**
     * Stop Cache
     *
     * Stops AR caching
     *
     * @return    void
     */
    public function stopCache()
    {
        $this->ar_caching = false;
    }

    /**
     * Flush Cache
     *
     * Empties the AR cache
     *
     * @return    void
     */
    public function flushCache()
    {
        $this->_resetRun(
            array(
                'ar_cache_select'  => array(),
                'ar_cache_from'    => array(),
                'ar_cache_join'    => array(),
                'ar_cache_where'   => array(),
                'ar_cache_like'    => array(),
                'ar_cache_groupby' => array(),
                'ar_cache_having'  => array(),
                'ar_cache_orderby' => array(),
                'ar_cache_set'     => array(),
                'ar_cache_exists'  => array()
            )
        );
    }

    /**
     * Merge Cache
     *
     * When called, this function merges any cached AR arrays with 
     * locally called ones.
     *
     * @return    void
     */
    private function _mergeCache()
    {
        if (count($this->ar_cache_exists) == 0) {
            return;
        }
        foreach ($this->ar_cache_exists as $val) {
            $ar_variable  = 'ar_' . $val;
            $ar_cache_var = 'ar_cache_' . $val;
            if (count($this->$ar_cache_var) == 0) {
                continue;
            }
            $this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
        }
    }

}

// END Crud
/* End of file Crud.php

/* Location: .Obullo/Database/Crud/Crud.php */