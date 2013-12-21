<?php
namespace Database_Pdo\Src\Crud {

    // --------------------------------------------------------------------
    
    /**
    * Like
    *
    * Called by like() or orlike()
    *
    * @access   private
    * @param    mixed
    * @param    mixed
    * @param    string
    * @return   void
    */
    function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
    {
        $crud = getInstance()->{\Db::$var};

        if ( ! is_array($field))
        {
            $field = array($field => $match);
        }
     
        foreach ($field as $k => $v)
        {
            $k = $crud->_protectIdentifiers($k);
            
            $prefix = (count($crud->ar_like) == 0) ? '' : $type;
        
            // Obullo changes ..
            // if not bind value ... 
            if( strpos($v, ':') === false || strpos($v, ':') > 0) // Obullo Changes...
            {
                $like_statement = $prefix." $k $not LIKE ".$crud->escapeLike($v, $side);
            } 
            else 
            {
                // !!IMPORTANT if pdo Bind value used , remove "%" operators..
                // don't do this->db->escape_like
                // because of user must be filter '%like%' values from outside.
               $crud->is_like_bind = true;
                
               $like_statement = $prefix." $k $not LIKE ".$v;   
            }
            
            // some platforms require an escape sequence definition for LIKE wildcards
            if ($crud->_like_escape_str != '')
            {
                $like_statement = $like_statement.sprintf($crud->_like_escape_str, $crud->_like_escape_chr);
            }
            
            $crud->ar_like[] = $like_statement;
            
            if ($crud->ar_caching === true)
            {
                $crud->ar_cache_like[]   = $like_statement;
                $crud->ar_cache_exists[] = 'like';
            }
        }
        
        return $crud;
    }

}