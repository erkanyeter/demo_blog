<?php

namespace Obullo\Tree;

use RunTimeException;

/**
 * Nested Set Model Tree Class
 * 
 * Modeled after https://github.com/olimortimer/ci-nested-sets
 * 
 * @category  Tree
 * @package   Db
 * @author    Obullo Framework <obulloframework@gmail.com>
 * @author    Ali Ihsan Caglayan <ihsancaglayan@gmail.com>
 * @author    Ersin Guvenc <eguvenc@gmail.com>
 * @copyright 2009-2014 Obullo
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL Licence
 * @link      http://obullo.com/package/tree
 */
Class Db
{
    /**
     * Table Constants
     */
    const TABLE_NAME  = 'nested_category';
    const PRIMARY_KEY = 'category_id';
    const TEXT        = 'name';
    const LEFT        = 'lft';
    const RIGHT       = 'rgt';

    /**
     * Protect sql query identifiers
     * default value is for mysql ( ` ).
     * 
     * @var string
     */
    public $escapeChar = '`';

    /**
     * $db Pdo object
     * 
     * @var object
     */
    public $db = null;

    /**
     * Tablename
     * 
     * @var string
     */
    public $tableName;

    /**
     * Column name primary key
     * 
     * @var string
     */
    public $primaryKey;

    /**
     * Column name text
     * 
     * @var string
     */
    public $text;

    /**
     * Column name lft
     * 
     * @var string
     */
    public $lft;

    /**
     * Column name rgt
     * 
     * @var string
     */
    public $rgt;

    /**
     * Sql query or cached sql query 
     * result array
     * 
     * @var object
     */
    protected $resultArray;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $c;

        $this->db = $c['db'];   // set database object.

        $this->tableName  = static::TABLE_NAME;  // set default values
        $this->primaryKey = static::PRIMARY_KEY;
        $this->text       = static::TEXT;
        $this->lft        = static::LEFT;
        $this->rgt        = static::RIGHT;
    }

    /**
     * You can set escape character to protect
     * database column names or identifiers.
     * 
     * @param string $char character
     * 
     * @return void
     */
    public function setEscapeChar($char)
    {
        $this->escapeChar = $char;
    }

    /**
     * Set database table name
     * 
     * @param array $table database table name
     *
     * @return void
     */
    public function setTablename($table)
    {
        $this->tableName = $table;
    }

    /**
     * Set primary key column name
     * 
     * @param string $key pk name
     *
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->primaryKey = $key;
    }

    /**
     * Set left column name
     * 
     * @param string $lft left column name
     *
     * @return void
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * Set right column name
     * 
     * @param string $rgt right column name
     *
     * @return void
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * Set text column name
     * 
     * @param string $text text column name
     *
     * @return void
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Empties the table currently in use - use with extreme caution!
     *
     * @return boolean
     */
    public function truncateTable()
    {
        return $this->db->exec(sprintf('TRUNCATE TABLE %s', $this->tableName));
    }

    /**
     * Adds the first entry to the table.
     * 
     * @param string $text  text name
     * @param array  $extra extra data
     * 
     * @return void
     */
    public function addTree($text, $extra = array())
    {
        $sql = "SELECT MAX(%s) AS %s FROM %s";
        $this->db->query(
            sprintf(
                $sql,
                $this->protect($this->rgt),
                $this->protect($this->lft),
                $this->tableName
            )
        );
        $result = $this->db->getRowArray();

        $data = array(
            $this->text => $text,
            $this->lft  => (isset($result[$this->lft])) ? $result[$this->lft] : 0 + 1,
            $this->rgt  => (isset($result[$this->lft])) ? $result[$this->lft] : 0 + 2,
        );
        
        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Insert to table.
     * 
     * @param string $tableName table name
     * @param array  $data      data
     * 
     * @return void
     */
    public function insert($tableName, $data)
    {
        $values = rtrim(str_repeat("?,", count($data)), ',');

        $sql = "INSERT INTO $tableName (".implode(',', array_keys($data)).") VALUES (".$values.");";
    
        $this->db->prepare($sql);
        $this->db->execute(array_values($data));
    }

    /**
     * Inserts a new node as the first child of the supplied parent node.
     * 
     * @param int    $lftValue lft column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function addChild($lftValue, $text, $extra = array())
    {
        $this->updateLeft(2, $lftValue + 1);
        $this->updateRight(2, $lftValue + 1);

        $data              = array();
        $data[$this->text] = $text;
        $data[$this->lft]  = $lftValue + 1;
        $data[$this->rgt]  = $lftValue + 2;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Same as addChild except the new node is added as the last child
     * 
     * @param int    $rgtValue rgt column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function appendChild($rgtValue, $text, $extra = array())
    {
        $this->updateLeft(2, $rgtValue);
        $this->updateRight(2, $rgtValue);
        
        $data              = array();
        $data[$this->text] = $text;
        $data[$this->lft]  = $rgtValue;
        $data[$this->rgt]  = $rgtValue + 1;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Inserts a new node as the first sibling of the supplied parent node.
     *
     * @param int    $lftValue lft column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function addSibling($lftValue, $text, $extra = array())
    {
        $this->updateLeft(2, $lftValue);
        $this->updateRight(2, $lftValue);
        
        $data              = array();
        $data[$this->text] = $text;
        $data[$this->lft]  = $lftValue;
        $data[$this->rgt]  = $lftValue + 1;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Insert a new node to the right of the supplied focusNode
     * 
     * @param int    $rgtValue rgt column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function appendSibling($rgtValue, $text, $extra = array())
    {
        $this->updateLeft(2, $rgtValue + 1);
        $this->updateRight(2, $rgtValue + 1);

        $data              = array();
        $data[$this->text] = $text;
        $data[$this->lft]  = $rgtValue + 1;
        $data[$this->rgt]  = $rgtValue + 2;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Deletes the given node (and any children) from the tree table.
     * 
     * @param int $lftValue lft value
     * @param int $rgtValue rgt value
     *
     * @return boolean
     */
    public function deleteNode($lftValue, $rgtValue)
    {
        $where = array(
            $this->lft . ' >=' => $lftValue,
            $this->rgt . ' <=' => $rgtValue,
        );
        $this->db->delete($this->tableName, $where);

        $this->updateLeft(2, ($lftValue - $rgtValue - 1));
        $this->updateRight(2, ($lftValue - $rgtValue - 1));
    }

    /**
     * Update node
     * 
     * @param int $category_id category id
     * @param int $data        data
     * 
     * @return void
     */
    public function updateNode($category_id, $data = array())
    {
        $update = '';
        foreach ($data as $key => $val) {
            $update .= $this->protect($key) . '=' . $this->db->escape($val) . ',';
        }
        $this->db->exec(
            sprintf(
                'UPDATE %s SET %s WHERE %s = %s',
                $this->protect($this->tableName),
                rtrim($update, ','),
                $this->protect($this->primaryKey),
                $category_id
            )
        );
    }

    /**
     * Move as first child
     * 
     * @param array $source source
     * @param array $target target
     * 
     * @return void
     */
    public function moveAsFirstChild($source, $target)
    {
        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->lft] + 1;

        /**
         * Modify Node
         */
        $this->updateLeft($sizeOfTree, $value);
        $this->updateRight($sizeOfTree, $value);

        /**
         * Extend current tree values
         */
        if ($source[$this->lft] >= $value) {
            $source[$this->lft] += $sizeOfTree;
            $source[$this->rgt] += $sizeOfTree;
        }

        /**
         * Modify Range
         */
        $this->updateLeft($value - $source[$this->lft], $source[$this->lft], "AND $this->lft <= " .$source[$this->rgt]);
        $this->updateRight($value - $source[$this->lft], $source[$this->lft], "AND $this->rgt <= " .$source[$this->rgt]);

        /**
         * Modify Node
         */
        $this->updateLeft(- $sizeOfTree, $source[$this->rgt] + 1);
        $this->updateRight(- $sizeOfTree, $source[$this->rgt] + 1);
    }

    /**
     * Move as last child
     * 
     * @param array $source source
     * @param array $target target
     * 
     * @return void
     */
    public function moveAsLastChild($source, $target)
    {
        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->rgt];

        /**
         * Modify Node
         */
        $this->updateLeft($sizeOfTree, $value);
        $this->updateRight($sizeOfTree, $value);

        /**
         * Extend current tree values
         */
        if ($source[$this->lft] >= $value) {
            $source[$this->lft] += $sizeOfTree;
            $source[$this->rgt] += $sizeOfTree;
        }

        /**
         * Modify Range
         */
        $this->updateLeft($value - $source[$this->lft], $source[$this->lft], "AND $this->lft <= " .$source[$this->rgt]);
        $this->updateRight($value - $source[$this->lft], $source[$this->lft], "AND $this->rgt <= " .$source[$this->rgt]);

        /**
         * Modify Node
         */
        $this->updateLeft(- $sizeOfTree, $source[$this->rgt] + 1);
        $this->updateRight(- $sizeOfTree, $source[$this->rgt] + 1);
    }

    /**
     * Move as next sibling
     * 
     * @param array $source source
     * @param array $target target
     * 
     * @return void
     */
    public function moveAsNextSibling($source, $target)
    {
        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->rgt] + 1;

        /**
         * Modify Node
         */
        $this->updateLeft($sizeOfTree, $value);
        $this->updateRight($sizeOfTree, $value);

        /**
         * Extend current tree values
         */
        if ($source[$this->lft] >= $value) {
            $source[$this->lft] += $sizeOfTree;
            $source[$this->rgt] += $sizeOfTree;
        }

        /**
         * Modify Range
         */
        $this->updateLeft($value - $source[$this->lft], $source[$this->lft], "AND $this->lft <= " .$source[$this->rgt]);
        $this->updateRight($value - $source[$this->lft], $source[$this->lft], "AND $this->rgt <= " .$source[$this->rgt]);

        /**
         * Modify Node
         */
        $this->updateLeft(- $sizeOfTree, $source[$this->rgt] + 1);
        $this->updateRight(- $sizeOfTree, $source[$this->rgt] + 1);
    }

    /**
     * Move as prev sibling
     * 
     * @param array $source source
     * @param array $target target
     * 
     * @return void
     */
    public function moveAsPrevSibling($source, $target)
    {
        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->lft];

        /**
         * Modify Node
         */
        $this->updateLeft($sizeOfTree, $value);
        $this->updateRight($sizeOfTree, $value);

        /**
         * Extend current tree values
         */
        if ($source[$this->lft] >= $value) {
            $source[$this->lft] += $sizeOfTree;
            $source[$this->rgt] += $sizeOfTree;
        }

        /**
         * Modify Range
         */
        $this->updateLeft($value - $source[$this->lft], $source[$this->lft], "AND $this->lft <= " .$source[$this->rgt]);
        $this->updateRight($value - $source[$this->lft], $source[$this->lft], "AND $this->rgt <= " .$source[$this->rgt]);

        /**
         * Modify Node
         */
        $this->updateLeft(- $sizeOfTree, $source[$this->rgt] + 1);
        $this->updateRight(- $sizeOfTree, $source[$this->rgt] + 1);
    }

    /**
     * Update left column value
     * 
     * @param int    $setValue   sql SET value
     * @param int    $whereValue sql WHERE condition value
     * @param string $attribute  extra sql conditions e.g. "AND rgt >= 1"
     * 
     * @return void
     */
    protected function updateLeft($setValue, $whereValue, $attribute = '')
    {
        $this->db->exec(
            sprintf(
                'UPDATE %s SET %s = %s + %s WHERE %s >= %s %s',
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $setValue,
                $this->protect($this->lft),
                $whereValue,
                $attribute
            )
        );
    }

    /**
     * Update right column value
     * 
     * @param int    $setValue   sql SET value
     * @param int    $whereValue sql WHERE condition value
     * @param string $attribute  extra sql conditions e.g. "AND rgt >= 1"
     * 
     * @return void
     */
    protected function updateRight($setValue, $whereValue, $attribute = '')
    {
        $this->db->exec(
            sprintf(
                'UPDATE %s SET %s = %s + %s WHERE %s >= %s %s',
                $this->protect($this->tableName),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $setValue,
                $this->protect($this->rgt),
                $whereValue,
                $attribute
            )
        );
    }

    /**
     * Protect identifiers using your escape character.
     * 
     * Escape character able to set using $this->setEscapeChar()
     * method.
     * 
     * @param string $identifier identifier
     * 
     * @return string
     */
    public function protect($identifier)
    {
        return $this->escapeChar.$identifier.$this->escapeChar;
    }

    /**
     * Append extra data.
     * 
     * @param array $data  data
     * @param array $extra extra data
     * 
     * @return array
     */
    public function appendExtraData($data, $extra)
    {
        if (count($extra) > 0) {
            $data = array_merge($data, $extra);
        }
        $newData = array();
        foreach ($data as $k => $v) {
            $newData[$this->protect($k)] = $v;
        }
        return $newData;
    }

    /**
     * Run sql query
     * 
     * @param string  $sql       sql query string
     * @param integer $cache_ttl expiration time
     * 
     * @return string sql query
     */
    public function query($sql, $cache_ttl)
    {
        if ($cache_ttl > 0) {
            $key = md5($sql);
            $this->cache = $c['cache'];
        }

        $this->db->query($sql);
        $this->resultArray = $this->db->resultArray();
    }

    /**
     * Fetch sql query results as array
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->resultArray;
    }

}


// END Db.php File
/* End of file Db.php

/* Location: .Obullo/Tree/Db.php */