<?php
namespace Validator\Src {

    // --------------------------------------------------------------------
    
    /**
     * Match one field to another
     *
	 * @access	public
	 * @param	string
	 * @param	field
	 * @return	bool
     */        
    function isUnique($str, $field)
    {
        $db = getInstance()->{\Db::$var};

        list($table, $field) = explode('.', $field);

		$db->limit(1);
		$db->where($field, $str);

		return $db->get($table)->count() === 0;
    }

}