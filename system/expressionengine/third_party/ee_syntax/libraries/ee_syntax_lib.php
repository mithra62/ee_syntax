<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once 'geshi/geshi.php';

class Ee_syntax_lib
{
	public function __construct()
	{
		$this->syntax_token = md5(uniqid(rand()));
		$this->syntax_matches = array();		
		$this->EE =& get_instance();
	}
	
	public function parse($data)
	{
		$data = preg_replace_callback(
			        "/\s*<pre(?:lang=[\"']([\w-]+)[\"']|line=[\"'](\d*)[\"']|escaped=[\"'](true|false)?[\"']|highlight=[\"']((?:\d+[,-])*\d+)[\"']|\s)+>(.*)<\/pre>\s*/siU",
			        array($this, '_syntax_substitute'),
			        $data
		);

		$data = preg_replace_callback(
			"/<p>\s*".$this->syntax_token."(\d{3})\s*<\/p>/si",
			array($this, '_syntax_highlight'),
			$data
     	);		
		return $data;
	}
	
	private function _syntax_highlight($match)
	{	 
	    $i = intval($match[1]);
	    $match = $this->syntax_matches[$i];
	    $language = strtolower(trim($match[1]));
	    $line = trim($match[2]);
	    $escaped = trim($match[3]);
	 
	    $code = $this->_code_trim($match[5]);
	    if ($escaped != "false") 
	    {
	    	$code = htmlspecialchars_decode($code);
	    }
	    
	    $run_language = $language;
	    if($language == "ee")
	    {
	    	//$run_language = 'html';	
	    }
	    //$code = htmlspecialchars_decode($code);
	    $this->geshi = new GeSHi($code, $run_language);
	        
	    $this->geshi->enable_keyword_links(false);
	 
	    $highlight = array();
	    if ( !empty($match[4]) )
	    {
	        $highlight = strpos($match[4],',') == false ? array($match[4]) : explode(',', $match[4]);
	 
			$h_lines = array();
			$total = sizeof($highlight);
			for( $i=0; $i<$total; $i++ )
			{
				$h_range = explode('-', $highlight[$i]);
		 
				if( sizeof($h_range) == 2 )
				{
					$h_lines = array_merge( $h_lines, range($h_range[0], $h_range[1]) );
				}
				else
				{
					array_push($h_lines, $highlight[$i]);
				}
			}
	
	        $this->geshi->highlight_lines_extra( $h_lines );
	    }
	    //END LINE HIGHLIGHT SUPPORT
	 
	    $output = "\n<div class=\"ee_syntax\">";
	    $code = $this->geshi->parse_code();
		if($language == "ee")
	    {
	    	$code = html_entity_decode($code);
	    }	    
	 
	    if ($line)
	    {
	        $output .= "<table><tr><td class=\"line_numbers\">";
	        $output .= $this->_line_numbers($code, $line);
	        $output .= "</td><td class=\"code\">";
	        $output .= $code;
	        $output .= "</td></tr></table>";
	    }
	    else
	    {
	        $output .= "<div class=\"code\">";
	        $output .= $code;
	        $output .= "</div>";
	    }
	 
	    $output .= "</div>\n";
	 
	    return $output;		
	}
	
	private function _line_numbers($code, $start)
	{
	    $line_count = count(explode("\n", $code));
	    $output = "<pre>";
	    for ($i = 0; $i < $line_count; $i++)
	    {
	        $output .= ($start + $i) . "\n";
	    }
	    $output .= "</pre>";
	    return $output;
	}	
	
	private function _syntax_substitute(&$match)
	{	
	    $i = count($this->syntax_matches);
	    $this->syntax_matches[$i] = $match;
	    return "\n\n<p>" . $this->syntax_token . sprintf("%03d", $i) . "</p>\n\n";
	}

	private function _code_trim($code)
	{
	    // special ltrim b/c leading whitespace matters on 1st line of content
	    $code = preg_replace("/^\s*\n/siU", "", $code);
	    $code = rtrim($code);
	    return $code;
	}	
}