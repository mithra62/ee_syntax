<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ee_syntax {

	public $return_data	= '';
	
	public function __construct()
	{
		$this->EE =& get_instance();		
	}
	
	public function add_head()
	{
		$theme_folder_url = $this->EE->config->item('theme_folder_url');
		if (substr($theme_folder_url, -1) != '/') 
		{
			$theme_folder_url .= '/';
		}
		
		$css_url = $theme_folder_url . "third_party/ee_syntax/styles/ee-syntax.css";
		return "\n".'<link rel="stylesheet" href="' . $css_url . '" type="text/css" media="screen" />'."\n";
	}
	
	public function filter()
	{
		$this->EE->load->library('ee_syntax_lib');
		$data = $this->EE->ee_syntax_lib->parse($this->EE->TMPL->tagdata);
		return $data;
	}
	
}