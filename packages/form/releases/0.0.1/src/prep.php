<?php
namespace Form\Src {

    // ------------------------------------------------------------------------

    /**
    * Form Prep
    *
    * Formats text so that it can be safely placed in a form field in the event it has HTML tags.
    *
    * @access   public
    * @param    string
    * @return   string
    */
    function prep($str = '', $field_name = '')
    {
        $form = getInstance()->form;

        static $prepped_fields = array();

        // if the field name is an array we do this recursively
        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = $form->prep($val);
            }

            return $str;
        }

        if ($str === '')
        {
            return '';
        }

        // we've already prepped a field with this name
        // @todo need to figure out a way to namespace this so
        // that we know the *exact* field and not just one with
        // the same name
        if (isset($prepped_fields[$field_name]))
        {
            return $str;
        }

        $str = htmlspecialchars($str);

        // In case htmlspecialchars misses these.
        $str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

        if ($field_name != '')
        {
            $prepped_fields[$field_name] = $field_name;
        }

        return $str;
    }

}