<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ee_syntax_upd { 

    public $version = '1.0'; 
    
    public $name = 'Ee_syntax';
    
    public $class = 'Ee_syntax';
         
    public function __construct() 
    { 
		$this->EE =& get_instance();
    } 
    
    public function install() 
	{
		$this->EE->load->dbforge();
	
		$data = array(
			'module_name' => $this->name,
			'module_version' => $this->version,
			'has_cp_backend' => 'n',
			'has_publish_fields' => 'n'
		);
	
		$this->EE->db->insert('modules', $data);		
		return TRUE;
	}  
	
	public function uninstall()
	{
		return TRUE;
	}	
	
	public function update($current = '')
	{
		if ($current == $this->version)
		{
			return TRUE;
		}	
		
		return TRUE;
	}	  
}