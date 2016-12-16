<?php

/**
 * App_Data_Model
 * 
 * @package App
 */

class Util extends CI_Model
{
	
  function __construct()
    {
        // Call the Model constructor
        parent::__construct();

    }	

	public function _debug($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	
}	
	