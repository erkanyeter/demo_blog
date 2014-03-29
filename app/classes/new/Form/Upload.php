<?php
Class Form_Upload  {

    // ------------------------------------------------------------------------

    /**
    * Upload Field
    *
    * Identical to the input function but adds the "file" type
    *
    * @access	public
    * @param	mixed
    * @param	string
    * @param	string
    * @return	string
    */
    public function __invoke($data = '', $value = '', $extra = '')
    {
        if ( ! is_array($data)) {
            $data = array('name' => $data);
        }
        
        $data['type'] = 'file';
        
        $form = \Form::getConfig();

        return sprintf($form['templates'][\Form::$template]['file'], getInstance()->form->input($data, $value, $extra));
    }
    
}