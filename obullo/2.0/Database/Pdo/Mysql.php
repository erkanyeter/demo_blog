<?php

namespace Obullo\Database\Pdo;

use PDO;

/**
 * Pdo Mysql Driver
 * 
 * @category  Database
 * @package   Adapter
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/database
 */
Class Mysql extends Adapter
{
    /**
     * The character used for escaping
     *
     * @var string
     */
    public $_escape_char = '`';

    /**
     * Clause and character used for LIKE escape sequences - not used in MySQL
     * 
     * @var string
     */
    public $_like_escape_str = '';
    /**
     * Clause and character used for LIKE escape sequences - not used in MySQL
     * @var string
     */
    public $_like_escape_chr = '';

    /**
     * Constructor
     * 
     * @param array $params connection parameters
     */
    public function __construct($params)
    {
        parent::__construct($params);

        $this->connect();
    }

    /**
     * Connect to pdo
     * 
     * @return void
     */
    public function connect()
    {
        // If connection is ok .. not need to again connect..
        if ($this->_conn) {
            return;
        }

        $port = empty($this->dbh_port) ? '' : ';port=' . $this->dbh_port;
        $dsn = empty($this->dsn) ? 'mysql:host=' . $this->hostname . $port . ';dbname=' . $this->database : $this->dsn;

        if (defined('PDO::MYSQL_ATTR_USE_BUFFERED_QUERY')) { // Automatically use buffered queries.
            $this->options[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
        }

        // array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $this->char_set") it occurs an error !
        $this->_pdo = $this->pdoConnect($dsn, $this->username, $this->password, $this->options);

        if (!empty($this->char_set)) {
            $this->_conn->exec("SET NAMES '" . $this->char_set . "'");
        }

        // We set exception attribute for always showing the pdo exceptions errors. (ersin)
        $this->_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // PDO::ERRMODE_SILENT
    }

    /**
     * Escape the SQL Identifiers
     *
     * This function escapes column and table names
     * 
     * @param string $item item
     * 
     * @return string
     */
    public function _escapeIdentifiers($item)
    {
        if ($this->_escape_char == '') {
            return $item;
        }
        foreach ($this->_reserved_identifiers as $id) {
            if (strpos($item, '.' . $id) !== false) {
                $str = $this->_escape_char . str_replace('.', $this->_escape_char . '.', $item);
                // remove duplicates if the user already included the escape
                return preg_replace('/[' . $this->_escape_char . ']+/', $this->_escape_char, $str);
            }
        }

        if (strpos($item, '.') !== false) {
            $str = $this->_escape_char . str_replace('.', $this->_escape_char . '.' . $this->_escape_char, $item) . $this->_escape_char;
        } else {
            $str = $this->_escape_char . $item . $this->_escape_char;
        }

        // remove duplicates if the user already included the escape
        return preg_replace('/[' . $this->_escape_char . ']+/', $this->_escape_char, $str);
    }

    /**
     * 
     *
     * @access   public
     * @param    string
     * @param    bool    
     * @return   string
     */
    
    /**
     * Escape string
     * 
     * @param string  $str  string
     * @param boolean $like whether or not the string will be used in a LIKE condition
     * @param string  $side direction
     * 
     * @return string
     */
    public function escapeStr($str, $like = false, $side = 'both')
    {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                $str[$key] = $this->escapeStr($val, $like);
            }
            return $str;
        }
        if ($like === true) {         // escape LIKE condition wildcards
            $str = str_replace(array('%', '_'), array('\\%', '\\_'), $str);

            switch ($side) {
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
        if ($this->prepare === true) {          // make sure is it bind value, if not ...
            if (strpos($str, ':') === false) {
                $str = $this->quote($str, PDO::PARAM_STR);
            }
        } else {
            $str = $this->quote($str, PDO::PARAM_STR);
        }
        return $str;
    }

    /**
     * Platform specific pdo quote function.
     * 
     * @param string $str  string
     * @param mixed  $type type
     * 
     * @return string
     */
    public function quote($str, $type = null)
    {
        return $this->_conn->quote($str, $type);
    }

    /**
     * From Tables
     *
     * This function implicitly groups FROM tables so there is no confusion
     * about operator precedence in harmony with SQL standards
     * 
     * @param array $tables values
     * 
     * @return string
     */
    public function _fromTables($tables)
    {
        if ( ! is_array($tables)) {
            $tables = array($tables);
        }
        return '(' . implode(', ', $tables) . ')';
    }

    /**
     * Insert statement
     *
     * Generates a platform-specific insert string from the supplied data
     *
     * @param string $table  the table name
     * @param array  $keys   the insert keys
     * @param array  $values the insert values
     * 
     * @return   string
     */
    public function _insert($table, $keys, $values)
    {
        return "INSERT INTO " . $table . " (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ")";
    }

    /**
     * Replace statement
     *
     * Generates a platform-specific replace string from the supplied data
     *
     * @param string $table  the table name
     * @param array  $keys   the insert keys
     * @param array  $values the insert values
     * 
     * @return  string
     */
    public function _replace($table, $keys, $values)
    {
        return "REPLACE INTO " . $table . " (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ")";
    }

    /**
     * Update statement
     *
     * Generates a platform-specific update string from the supplied data
     *
     * @param string $table   the table name
     * @param array  $values  the update data
     * @param array  $where   the where clause
     * @param array  $orderby the orderby clause
     * @param array  $limit   the limit clause
     * 
     * @return string
     */
    public function _update($table, $values, $where, $orderby = array(), $limit = false)
    {
        foreach ($values as $key => $val) {
            $valstr[] = $key . " = " . $val;
        }
        $limit = (!$limit) ? '' : ' LIMIT ' . $limit;
        $orderby = (count($orderby) >= 1) ? ' ORDER BY ' . implode(", ", $orderby) : '';

        $sql = "UPDATE " . $table . " SET " . implode(', ', $valstr);
        $sql .= ($where != '' AND count($where) >= 1) ? " WHERE " . implode(" ", $where) : '';
        $sql .= $orderby . $limit;
        return $sql;
    }

    /**
     * Delete statement
     *
     * Generates a platform-specific delete string from the supplied data
     *
     * @param string $table the table name
     * @param array  $where the where clause
     * @param string $like  the like clause
     * @param string $limit the limit clause
     * 
     * @return string
     */
    public function _delete($table, $where = array(), $like = array(), $limit = false)
    {
        $conditions = '';
        if (count($where) > 0 OR count($like) > 0) {
            $conditions = "\nWHERE ";
            $conditions .= implode("\n", $where);
            if (count($where) > 0 AND count($like) > 0) {
                $conditions .= " AND ";
            }
            $conditions .= implode("\n", $like);
        }
        $limit = (!$limit) ? '' : ' LIMIT ' . $limit;
        return "DELETE FROM " . $table . $conditions . $limit;
    }

    /**
     * Limit string
     * Generates a platform-specific LIMIT clause
     * 
     * @param string  $sql    query
     * @param integer $limit  number limit
     * @param integer $offset number offset
     * 
     * @return string
     */
    public function _limit($sql, $limit, $offset)
    {
        if ($offset == 0) {
            $offset = '';
        } else {
            $offset .= ", ";
        }
        return $sql . "LIMIT " . $offset . $limit;
    }

}

// END Mysql Class
/* End of file Mysql.php

/* Location: .Obullo/Database/Pdo/Mysql.php */