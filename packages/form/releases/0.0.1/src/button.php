<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Button
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	string
    * @return	string
    */
    function button($data = '', $content = '', $extra = '')
    {
        $form = \Form::getConfig();

        $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'type' => 'button');

        if ( is_array($data) AND isset($data['content']))
        {
            $content = $data['content'];
            unset($data['content']); // content is not an attribute
        }

        return sprintf($form['templates'][\Form::$template]['button'], "<button ".\Form::_parseFormAttributes($data, $defaults).$extra.">".$content."</button>");
    }
    
}