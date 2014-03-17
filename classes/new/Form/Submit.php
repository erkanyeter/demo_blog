<?php
Class Form_Submit {

    // ------------------------------------------------------------------------

    /**
    * Submit Button
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	string
    * @return	string
    */
    public function __invoke($data = '', $value = '', $extra = '')
    {
        $defaults = array('type' => 'submit', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => translate($value));

        $form = \Form::getConfig();

        return sprintf($form['templates'][\Form::$template]['submit'], '<input '.\Form::_parseFormAttributes($data, $defaults).$extra.' />');
    }
    
}