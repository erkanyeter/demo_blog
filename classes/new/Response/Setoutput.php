<?php
Class Response_SetOutput {

    // --------------------------------------------------------------------
    
    /**
    * Set Output
    *
    * Sets the output string
    *
    * @access    public
    * @param     string
    * @return    void
    */    
    public function __invoke($output)
    {   
        global $response;

        $response->final_output = $output;
    }
}