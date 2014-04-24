<?php

/**
 * Nested Set Tree Model
 */
Class Tree_Category
{
    public $escapeChar = '`';

    /**
     * Parameter constants
     */
    const TABLE_NAME  = 'db.tablename';      // nested_category';
    const PRIMARY_KEY = 'db.primary_key';    // category_id;
    const PARENT_ID   = 'db.parent_id';      // category_id;
    const TEXT        = 'db.text';           // category_id;
    const LEFT        = 'db.left';           // lft
    const RIGHT       = 'db.right';          // rgt

    /**
     * $db Pdo object
     * @var object
     */
    public $db = null;

    public $tableName  = 'nested_category';
    public $primaryKey = 'category_id';
    public $parentId   = 'parent_id';
    public $text       = 'name';
    public $lft        = 'lft';
    public $rgt        = 'rgt';

    /**
     * Allowed column names
     * @var array
     */
    public $allowedColumnKeys = array(
        self::TABLE_NAME,
        self::PRIMARY_KEY,
        self::PARENT_ID,
        self::TEXT,
        self::LEFT,
        self::RIGHT
    );

    /**
     * Constructor
     * 
     * @param array $params params
     */
    public function __construct($params = array())
    {
        global $c;

        $this->db = $c['db'];

        if (count($params) > 0 AND  in_array($this->allowedColumnKeys, $val)) {
            $this->tableName  = $params[self::TABLE_NAME];
            $this->primaryKey = $params[self::PRIMARY_KEY];
            $this->parentId   = $params[self::PARENT_ID];
            $this->text       = $params[self::TEXT];
            $this->lft        = $params[self::LEFT];
            $this->rgt        = $params[self::RIGHT];
        }
    }

    /**
     * You can set escape character to protect
     * database column names or identifiers.
     * 
     * @param string $char char
     * 
     * @return void
     */
    public function setEscapeChar($char)
    {
        $this->escapeChar = $char;
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
     * Adds the first entry to the table.
     * 
     * @param string $text  text name
     * @param array  $extra extra data
     * 
     * @return void
     */
    public function insertTree($text, $extra = array())
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

        $sql = "INSERT INTO $tableName (".implode(',', array_keys($data)).") VALUES (".$values.");";
    
        $this->db->prepare($sql);
        $this->db->execute(array_values($data));
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
     * Inserts a new node as the first child of the supplied parent node.
     * 
     * @param int    $id       parent id
     * @param int    $lftValue lft column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function insertFirstChild($id, $lftValue, $text, $extra = array())
    {
        $update_lft = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s + 1";
        $update_rgt = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s + 1";

        $this->db->exec(
            sprintf(
                $update_lft,
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $lftValue
            )
        );
        $this->db->exec(
            sprintf(
                $update_rgt,
                $this->protect($this->tableName),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $lftValue
            )
        );
        $data = array();
        $data[$this->parentId] =   $id;
        $data[$this->text]     =   $text;
        $data[$this->lft]      =   $lftValue + 1;
        $data[$this->rgt]      =   $lftValue + 2;

        $data = $this->appendExtraData($data, $extra);

        $this->insert($this->tableName, $data);
    }

    /**
     * Same as insertFirstChild except the new node is added as the last child
     * 
     * @param int    $id       parent id
     * @param int    $rgtValue rgt column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function appendNewChild($id, $rgtValue, $text, $extra = array())
    {
        $update_lft = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s";
        $update_rgt = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s";

        $this->db->exec(
            sprintf(
                $update_lft,
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $rgtValue
            )
        );
        $this->db->exec(
            sprintf(
                $update_rgt,
                $this->protect($this->tableName),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $rgtValue
            )
        );
        $data = array();
        $data[$this->parentId] =   $id;
        $data[$this->text]     =   $text;
        $data[$this->lft]      =   $rgtValue;
        $data[$this->rgt]      =   $rgtValue + 1;

        $data = $this->appendExtraData($data, $extra);
        $this->insert($this->tableName, $data);
    }

    /**
     * Inserts a new node as the first sibling of the supplied parent node.
     * 
     * @param int    $id       parent id
     * @param int    $lftValue lft column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function insertSibling($id, $lftValue, $text, $extra = array())
    {
        $update_lft = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s";
        $update_rgt = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s";

        $this->db->exec(
            sprintf(
                $update_lft,
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $lftValue
            )
        );
        $this->db->exec(
            sprintf(
                $update_rgt,
                $this->protect($this->tableName),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $lftValue
            )
        );
        $data = array();
        $data[$this->parentId] =   $id;
        $data[$this->text]     =   $text;
        $data[$this->lft]      =   $lftValue;
        $data[$this->rgt]      =   $lftValue + 1;

        $data = $this->appendExtraData($data, $extra);
        $this->insert($this->tableName, $data);
    }

    /**
     * Adds a new node to the right of the supplied focusNode
     * 
     * @param int    $id       parent id
     * @param int    $rgtValue rgt column value
     * @param string $text     value
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function appendSibling($id, $rgtValue, $text, $extra = array())
    {
        $update_lft = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s + 1";
        $update_rgt = "UPDATE %s SET %s = %s + 2 WHERE %s >= %s + 1";

        $this->db->exec(
            sprintf(
                $update_lft,
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $rgtValue
            )
        );
        $this->db->exec(
            sprintf(
                $update_rgt,
                $this->protect($this->tableName),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $rgtValue
            )
        );

        $data = array();
        $data[$this->parentId] =   $id;
        $data[$this->text]     =   $text;
        $data[$this->lft]      =   $rgtValue + 1;
        $data[$this->rgt]      =   $rgtValue + 2;

        $data = $this->appendExtraData($data, $extra);
        $this->insert($this->tableName, $data);
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

        $update_lft = "UPDATE %s SET %s = %s + %s WHERE %s >= %s";
        $update_rgt = "UPDATE %s SET %s = %s + %s WHERE %s >= %s";

        $newValue = ($lftValue - $rgtValue - 1);

        $this->db->exec(
            sprintf(
                $update_lft,
                $this->protect($this->tableName),
                $this->protect($this->lft),
                $this->protect($this->lft),
                $newValue,
                $this->protect($this->lft),
                $rgtValue
            )
        );
        $this->db->exec(
            sprintf(
                $update_rgt,
                $this->protect($this->tableName),
                $this->protect($this->rgt),
                $this->protect($this->rgt),
                $newValue,
                $this->protect($this->rgt),
                $rgtValue
            )
        );
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
        $sql = "UPDATE %s SET %s WHERE %s = %s";

        $this->db->exec(
            sprintf(
                $sql,
                $this->protect($this->tableName),
                rtrim($update, ','),
                $this->protect($this->primaryKey),
                $category_id
            )
        );
    }


}