<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class ELK_Loader extends CI_Loader {

	/**
	 *
	 */
	public function __construct()
	{
		spl_autoload_register(array($this, 'autoload'));	
        parent::__construct();
	}

	public function autoload($className) {
		if (file_exists(APPPATH."models/".strtolower($className).EXT)) {  
        	include_once(APPPATH."models/".strtolower($className).EXT);  
    	}  
	}
}

/* End of file ELK_Loader.php */
/* Location: ./application/library/ELK_Loader.php */
