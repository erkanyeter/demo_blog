<?php
/*
                // Basit bir sınıf tanımlayalım
                class Form_Button
                {
                    public $foo;

                    public function __invoke($a= null)
                    {
                        return $a;
                    }
                    
                }

                $a = new Form_Button;
                echo $a('as');

 */

Class Form_Button {

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
    public function __invoke($data = '', $content = '', $extra = '')
    {
        $form = \Form::getConfig();

        $defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'type' => 'button');

        if ( is_array($data) AND isset($data['content']))
        {
            $content = $data['content'];
            unset($data['content']); // content is not an attribute
        }

        return sprintf($form['templates'][\Form::$template]['button'], "<button ".\Form::_parseFormAttributes($data, $defaults).$extra.">".translate($content)."</button>");
    }
    
}
