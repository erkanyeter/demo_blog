<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Multi-select menu
    *
    * @access	public
    * @param	string
    * @param	array
    * @param	mixed
    * @param	string
    * @return	type
    */
    function multiSelect($name = '', $options = array(), $selected = array(), $extra = '')
    {
        if ( ! strpos($extra, 'multiple'))
        {
            $extra .= ' multiple="multiple"';
        }

        return getInstance()->form->dropdown($name, $options, $selected, $extra);
    }
    
}