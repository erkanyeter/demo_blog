<?php
Class Url_ParseAttributes {

    // ------------------------------------------------------------------------

    /**
    * Parse out the attributes
    *
    * Some of the functions use this
    *
    * @access	private
    * @param	array
    * @param	bool
    * @return	string
    */
    public function __invoke($attributes, $javascript = false)
    {
        if (is_string($attributes)) {
            return ($attributes != '') ? ' '.$attributes : '';
        }

        $att = '';
        foreach ($attributes as $key => $val) {
            if ($javascript == true) {
                $att .= $key . '=' . $val . ',';
            } else {
                $att .= ' ' . $key . '="' . $val . '"';
            }
        }

        if ($javascript == true AND $att != '') {
            $att = substr($att, 0, -1);
        }

        return $att;
    }

}