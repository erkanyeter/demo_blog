<?php
Class Form_Radio {

    // ------------------------------------------------------------------------

    /**
    * Radio Button
    *
    * @access   public
    * @param    mixed
    * @param    string
    * @param    bool
    * @param    string
    * @return   string
    */
    public function __invoke($data = '', $value = '', $checked = false, $extra = '')
    {
        if ( ! is_array($data)) {
            $data = array('name' => $data); 
        }

        $data['type'] = 'radio';
        
        return getInstance()->form->checkbox($data, $value, $checked, $extra);
    }
    
}