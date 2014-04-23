<?php

/**
 * 
 */
Class Nested_Category
{
    const TABLE_NAME  = 'db.tablename';            // nested_category';
    const PRIMARY_KEY = 'db.primary_key';    // category_id;
    const PARENT_ID   = 'db.parent_id';      // category_id;
    const TEXT        = 'db.text';           // category_id;
    const LEFT        = 'db.left';           // lft
    const RIGHT       = 'db.right';          // rgt

    /**
     * $db Db object
     * @var object
     */
    public $db = null;

    public $tableName  = 'nested_category';
    public $primaryKey = 'category_id';
    public $parentId   = 'parent_id';
    public $text       = 'name';
    public $lft        = 'lft';
    public $rgt        = 'rgt';

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
        $this->db = $c['crud'];

        if (count($params) > 0) {

            if ( ! in_array($this->allowedColumnKeys, $val)) {
                throw new Exception(sprintf('%s is not an allowed parameter.', $val));
            }
            $this->tableName  = $params[self::TABLE_NAME];
            $this->primaryKey = $params[self::PRIMARY_KEY];
            $this->parentId   = $params[self::PARENT_ID];
            $this->text       = $params[self::TEXT];
            $this->lft        = $params[self::LEFT];
            $this->rgt        = $params[self::RIGHT];
        }
    }

    /**
     * Adds the first entry to the table.
     * 
     * @param string $textName text name
     * @param array  $extra    extra data
     * 
     * @return void
     */
    public function buildTree($textName, $extra = array())
    {
        $this->db->query("SELECT MAX($this->rgt) AS $this->lft FROM $this->tableName");
        $result = $this->db->getRowArray();

        $data = array(
            $this->parentId => 0,
            $this->text     => $textName,
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
        return $data;
    }

    /**
     * Inserts a new node as the first child of the supplied parent node.
     * 
     * @param int    $id       parent id
     * @param int    $lftValue lft column value
     * @param string $textName value
     * 
     * @return void
     */
    public function insertFirstChild($id, $lftValue, $textName)
    {
        $update_lft = "UPDATE $this->tableName SET lft = lft + 2 WHERE lft >= $lftValue + 1";
        $update_rgt = "UPDATE $this->tableName SET rgt = rgt + 2 WHERE rgt >= $lftValue + 1";

        $this->db->exec($update_lft);
        $this->db->exec($update_rgt);

        $data = array();
        $data[$this->parentId] =   $id;
        $data[$this->text] =   $textName;
        $data[$this->lft]      =   $lftValue + 1;
        $data[$this->rgt]      =   $lftValue + 2;

        $this->insert($this->tableName, $data);
    }

    public function appendChild()
    {

    }

}