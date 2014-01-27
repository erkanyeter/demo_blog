<?php

/**
 * Web Results Json Response 
 * Decoder
 * 
 * @package       packages
 * @subpackage    web_result_json
 * @category      web_service
 * @link
 */

Class Web_Results_Json {
	
	protected $raw;  // raw data output

	/**
	 * Constructor
	 * 
	 * @param mixed $rows result data
	 */
	public function __construct($raw)
	{
		$this->raw = $raw; // assign raw data
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch all results as object.
	 * 
	 * @return object
	 */
	public function getResult()
	{
		return json_decode($this->raw);
	}
	
    // ------------------------------------------------------------------------

	/**
	 * Fetch all results as array
	 * 
	 * @return array
	 */
	public function getResultArray()
	{
		return json_decode($this->raw, true);
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch row as object
	 * 
	 * @param  integer $n [description]
	 * @return [type]     [description]
	 */
	public function getRow()
	{
		$rows = $this->getResultArray();

		return (isset($rows[0])) ? (object)$rows[0] : (object)$rows;
	}

    // ------------------------------------------------------------------------
	
	/**
	 * Fetch results as array
	 * 
	 * @param  integer $n
	 * @return array
	 */
	public function getRowArray()
	{
		$rows = $this->getResultArray();

		return (isset($rows[0])) ? $rows[0] : (array)$rows;
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch first row as object
	 * 
	 * @return object
	 */
	public function getFirstRow()
	{
		$result = $this->getResultArray();

        if (count($result) == 0)
        {
            return $result;
        }
        
        return $result[0];
	}

    // ------------------------------------------------------------------------
	
	/**
	 * Fetch previous row as object
	 * 
	 * @return object
	 */
	public function getPreviousRow()
	{
		$result = $this->getResultArray();

        if (count($result) == 0)
        {
            return $result;
        }

        if (isset($result[$this->current_row - 1]))
        {
            --$this->current_row;
        }
        
        return $result[$this->current_row];

	}
	
    // ------------------------------------------------------------------------

	/**
	 * Fetch next row as object
	 * 
	 * @return object
	 */
	public function getNextRow()
	{
		$result = $this->getResultArray();

        if(count($result) == 0)
        {
            return $result;
        }
        
        if(isset($result[$this->current_row + 1]))
        {
            ++$this->current_row;
        }

        return $result[$this->current_row];
	}

    // ------------------------------------------------------------------------

	/**
	 * Fetch last row as object
	 * 
	 * @return object
	 */
	public function getLastRow()
	{
		$result = $this->getResultArray();

        if (count($result) == 0)
        {
            return $result;
        }

        return $result[count($result) - 1];
	}
	
    // ------------------------------------------------------------------------
    
	/**
	 * Get number of rows
	 * 
	 * @return integer
	 */
	public function getCount()
	{
		$result = $this->getResultArray();
		$count  =  count($result);

		return (int)$count;
	}

}

// END Web Results Json

/* End of file web_result_json.php */
/* Location: ./packages/web_results_json/releases/0.0.1/web_results_json.php */