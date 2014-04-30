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
 *
 * https://github.com/olimortimer/ci-nested-sets/blob/master/Nested_set.php
 * http://framework.zend.com/wiki/display/ZFPROP/Zend_Db_NestedSet+-+Graham+Anderson
 * https://github.com/fpietka/Zend-Nested-Set/blob/master/library/Nestedset/Model.php
 * http://ftp.nchu.edu.tw/MySQL/tech-resources/articles/hierarchical-data.html
 * 
 */
Class Db
{
    /**
     * Table Constants
     */
    const TABLE_NAME  = 'nested_category';
    const PRIMARY_KEY = 'category_id';
    const PARENT_ID   = 'parent_id';
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
     * Column name parent_id
     * 
     * @var string
     */
    public $parentId;

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
     * $cache Cache object
     * 
     * @var object
     */
    public $cache;

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
        $this->parentId   = static::PARENT_ID;
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
            $this->parentId => 0,
            $this->text     => $text,
            $this->lft      => (isset($result[$this->lft])) ? $result[$this->lft] : 0 + 1,
            $this->rgt      => (isset($result[$this->lft])) ? $result[$this->lft] : 0 + 2,
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
        $sql    = "INSERT INTO $tableName (".implode(',', array_keys($data)).") VALUES (".$values.");";
    
        $this->db->prepare($sql);
        $this->db->execute(array_values($data));
    }

    /**
     * Get data
     * 
     * @param int $category_id primary key value
     * 
     * @return array sql data
     */
    public function getRow($category_id)
    {
        $this->db->query(
            sprintf(
                'SELECT * FROM %s WHERE %s = %s LIMIT 1',
                $this->protect($this->tableName),
                $this->protect($this->primaryKey),
                $this->db->escape((int)$category_id)
            )
        );
        return $this->db->getRowArray();
    }

    /**
     * Inserts a new node as the first child of the supplied parent node.
     * 
     * @param int    $category_id primary key column value
     * @param string $text        value
     * @param array  $extra       extra data
     * 
     * @return void
     */
    public function addChild($category_id, $text, $extra = array())
    {
        $row      = $this->getRow((int)$category_id);
        $lftValue = $row[$this->lft];

        $this->updateLeft(2, $lftValue + 1);
        $this->updateRight(2, $lftValue + 1);

        $data                  = array();
        $data[$this->parentId] = $category_id;
        $data[$this->text]     = $text;
        $data[$this->lft]      = $lftValue + 1;
        $data[$this->rgt]      = $lftValue + 2;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Same as addChild except the new node is added as the last child
     *
     * @param int    $category_id primary key column value
     * @param string $text        value
     * @param array  $extra       extra data
     * 
     * @return void
     */
    public function appendChild($category_id, $text, $extra = array())
    {
        $row      = $this->getRow((int)$category_id);
        $rgtValue = $row[$this->rgt];

        $this->updateLeft(2, $rgtValue);
        $this->updateRight(2, $rgtValue);
        
        $data                  = array();
        $data[$this->parentId] = $category_id;
        $data[$this->text]     = $text;
        $data[$this->lft]      = $rgtValue;
        $data[$this->rgt]      = $rgtValue + 1;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Inserts a new node as the first sibling of the supplied parent node.
     *
     * @param int    $category_id primary key column value
     * @param string $text        value
     * @param array  $extra       extra data
     * 
     * @return void
     */
    public function addSibling($category_id, $text, $extra = array())
    {
        $row      = $this->getRow((int)$category_id);
        $lftValue = $row[$this->lft];

        $this->updateLeft(2, $lftValue);
        $this->updateRight(2, $lftValue);
        
        $data                  = array();
        $data[$this->parentId] = $row[$this->parentId];
        $data[$this->text]     = $text;
        $data[$this->lft]      = $lftValue;
        $data[$this->rgt]      = $lftValue + 1;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Insert a new node to the right of the supplied focusNode
     * 
     * @param int    $category_id primary key column value
     * @param string $text        value
     * @param array  $extra       extra data
     * 
     * @return void
     */
    public function appendSibling($category_id, $text, $extra = array())
    {
        $row      = $this->getRow((int)$category_id);
        $rgtValue = $row[$this->rgt];

        $this->updateLeft(2, $rgtValue + 1);
        $this->updateRight(2, $rgtValue + 1);

        $data                  = array();
        $data[$this->parentId] = $row[$this->parentId];
        $data[$this->text]     = $text;
        $data[$this->lft]      = $rgtValue + 1;
        $data[$this->rgt]      = $rgtValue + 2;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Deletes the given node (and any children) from the tree table.
     * 
     * @param int $category_id primary key value
     *
     * @return boolean
     */
    public function deleteNode($category_id)
    {
        $row      = $this->getRow((int)$category_id);
        $lftValue = $row[$this->lft];
        $rgtValue = $row[$this->rgt];

        $this->db->query(
            sprintf(
                'DELETE FROM %s WHERE %s >= %s AND  %s <= %s',
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->db->escape($lftValue),
                $this->protect($this->rgt),
                $this->db->escape($rgtValue)
            )
        );
        $this->updateLeft(($lftValue - $rgtValue - 1), $rgtValue + 1);
        $this->updateRight(($lftValue - $rgtValue - 1), $rgtValue + 1);
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
                $this->db->escape($category_id)
            )
        );
    }

    /**
     * Update parent id
     * 
     * @param int $source      source data
     * @param int $category_id target category_id
     * 
     * @return void
     */
    public function updateParentId($source, $category_id)
    {
        if (isset($source[$this->parentId]) AND is_numeric($source[$this->parentId])) {
            $parent_id = $source[$this->parentId];
        } else {
            $parent_id = $this->getParentId($source[$this->primaryKey]);
        }

        if ($parent_id == $category_id) {
            return;
        }
        $this->db->exec(
            sprintf(
                'UPDATE %s SET %s = %s WHERE %s = %s',
                $this->protect($this->tableName),
                $this->protect($this->parentId),
                $this->db->escape($category_id),
                $this->protect($this->primaryKey),
                $this->db->escape($source[$this->primaryKey])
            )
        );
    }

    /**
     * Move as first child
     * 
     * @param array $sourceId source primary key value (category_id)
     * @param array $targetId target primary key value (category_id)
     * 
     * @return void
     */
    public function moveAsFirstChild($sourceId, $targetId)
    {
        $source     = $this->getRow((int)$sourceId);
        $target     = $this->getRow((int)$targetId);
        
        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->lft] + 1;

        $this->updateParentId($source, $target[$this->primaryKey]);

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
     * @param array $sourceId source primary key value (category_id)
     * @param array $targetId target primary key value (category_id)
     * 
     * @return void
     */
    public function moveAsLastChild($sourceId, $targetId)
    {
        $source     = $this->getRow((int)$sourceId);
        $target     = $this->getRow((int)$targetId);

        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->rgt];

        $this->updateParentId($source, $target[$this->primaryKey]);

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
     * @param array $sourceId source primary key value (category_id)
     * @param array $targetId target primary key value (category_id)
     * 
     * @return void
     */
    public function moveAsNextSibling($sourceId, $targetId)
    {
        $source     = $this->getRow((int)$sourceId);
        $target     = $this->getRow((int)$targetId);

        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->rgt] + 1;

        $this->updateParentId($source, $target[$this->parentId]);

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
     * @param array $sourceId source primary key value (category_id)
     * @param array $targetId target primary key value (category_id)
     * 
     * @return void
     */
    public function moveAsPrevSibling($sourceId, $targetId)
    {
        $source     = $this->getRow((int)$sourceId);
        $target     = $this->getRow((int)$targetId);

        $sizeOfTree = $source[$this->rgt] - $source[$this->lft] + 1;
        $value      = $target[$this->lft];

        $this->updateParentId($source, $target[$this->parentId]);

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
        return $this->escapeChar . $identifier . $this->escapeChar;
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
     * Get all tree
     * 
     * @param integer $nodeId node id
     * 
     * @return array
     */
    public function getAllTree($nodeId = 1)
    {
        $columnName = $this->primaryKey;
        if (is_string($nodeId)) {
            $columnName = $this->text;
        }

        $sql = sprintf(
            'SELECT node.%s
                FROM %s AS node,
                     %s AS parent
                WHERE node.%s BETWEEN parent.%s
                AND parent.%s
                AND parent.%s = %s
                ORDER BY node.%s',
            $this->protect($this->text),
            $this->protect($this->tableName),
            $this->protect($this->tableName),
            $this->protect($this->lft),
            $this->protect($this->lft),
            $this->protect($this->rgt),
            $columnName,
            $this->db->escape($nodeId),
            $this->lft
        );

        $this->db->query($sql);
        return $this->db->resultArray();
    }

    /**
     * Get depth of sub tree
     * 
     * @param mix $nodeId node id
     * 
     * @return array
     */
    public function getDepthOfSubTree($nodeId = 1)
    {
        $columnName = $this->primaryKey;
        if (is_string($nodeId)) {
            $columnName = $this->text;
        }

        $sql = sprintf(
            'SELECT node.%s, (COUNT(parent.%s) - (sub_tree.depth + 1)) AS depth
                FROM %s AS node,
                     %s AS parent,
                     %s AS sub_parent,
                    (
                        SELECT node.%s, (COUNT(parent.%s) - 1) AS depth
                        FROM %s AS node,
                             %s AS parent
                        WHERE node.%s BETWEEN parent.%s AND parent.%s
                        AND node.%s = %s
                        GROUP BY node.%s
                        ORDER BY node.%s
                    ) AS sub_tree
                WHERE node.%s BETWEEN parent.%s AND parent.%s
                    AND node.%s BETWEEN sub_parent.%s AND sub_parent.%s
                    AND sub_parent.%s = sub_tree.%s
                GROUP BY node.%s
                HAVING depth > 0
                ORDER BY node.%s',
            $this->protect($this->text),
            $this->protect($this->text),
            $this->protect($this->tableName),
            $this->protect($this->tableName),
            $this->protect($this->tableName),
            $this->protect($this->text),
            $this->protect($this->text),
            $this->protect($this->tableName),
            $this->protect($this->tableName),
            $this->protect($this->lft),
            $this->protect($this->lft),
            $this->protect($this->rgt),
            $columnName,
            $this->db->escape($nodeId),
            $columnName,
            $this->protect($this->lft),
            $this->protect($this->lft),
            $this->protect($this->lft),
            $this->protect($this->rgt),
            $this->protect($this->lft),
            $this->protect($this->lft),
            $this->protect($this->rgt),
            $this->protect($this->text),
            $this->protect($this->text),
            $columnName,
            $this->protect($this->lft)
        );
        $this->db->query($sql);
        return $this->db->resultArray();
    }

    /**
     * Get depth of all tree
     * 
     * [0] => Array
     *  (
     *      [name] => Electronics
     *      [parent_id] => 0
     *      [depth] => 0
     *  )
     *
     *  [1] => Array
     *  (
     *      [name] => Portable Electronics
     *      [parent_id] => 1
     *      [depth] => 1
     *  )
     * 
     * @return array
     */
    public function getDepthOfAllTree()
    {
        $sql = sprintf(
            'SELECT node.%s,node.%s, (COUNT(parent.%s) - 1) AS depth
                FROM %s AS node,
                     %s AS parent
                WHERE node.%s BETWEEN parent.%s
                AND parent.%s
                GROUP BY node.%s
                ORDER BY node.%s',
            $this->protect($this->text),
            $this->protect($this->parentId),
            $this->protect($this->text),
            $this->protect($this->tableName),
            $this->protect($this->tableName),
            $this->protect($this->lft),
            $this->protect($this->lft),
            $this->protect($this->rgt),
            $this->protect($this->text),
            $this->protect($this->lft)
        );
        $this->db->query($sql);
        return $this->db->resultArray();
    }

    /**
     * Return a root node
     * 
     * @return array
     */
    public function getRoot()
    {
        $sql = sprintf(
            'SELECT %s FROM %s WHERE %s = 0',
            $this->protect($this->text),
            $this->protect($this->tableName),
            $this->protect($this->parentId)
        );
        $this->db->query($sql);
        return $this->db->resultArray();
    }

    /**
     * Return all the siblings of this node.
     * 
     * @param int $category_id category id
     * 
     * @return array
     */
    public function getSiblings($category_id)
    {
        $parent_id = $this->getParentId((int)$category_id);

        $sql = sprintf(
            'SELECT %s FROM %s WHERE %s = %s',
            $this->protect($this->text),
            $this->protect($this->tableName),
            $this->protect($this->parentId),
            $this->db->escape($parent_id[$this->parentId])
        );
        $this->db->query($sql);
        return $this->db->resultArray();
    }

    /**
     * Get parent id
     * 
     * @param int $category_id category id
     * 
     * @return array
     */
    public function getParentId($category_id)
    {
        $sql = sprintf(
            'SELECT %s FROM %s WHERE %s = %s LIMIT 1',
            $this->protect($this->parentId),
            $this->protect($this->tableName),
            $this->protect($this->primaryKey),
            $this->db->escape((int)$category_id)
        );
        $this->db->query($sql);
        return $this->db->rowArray();
    }

    /**
     * Run sql query
     * 
     * @param string  $sql sql query string
     * @param integer $ttl expiration time
     * 
     * @return string sql query
     */
    public function query($sql, $ttl)
    {
        if ($cache_ttl > 0) {
            $key = md5($sql);
            $this->cache = $c['cache'];
        }

        $this->db->query($sql);
        $this->resultArray = $this->db->resultArray();
    }

}


// END Db.php File
/* End of file Db.php

/* Location: .Obullo/Tree/Db.php */