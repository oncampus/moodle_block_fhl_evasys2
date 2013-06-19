<?php
require_once($CFG->dirroot.'/blocks/fhl_evasys/evasys_lib.php');

class block_fhl_evasys2 extends block_base {
	
	public function init() {
		$this->title = get_string('default_title', 'block_fhl_evasys2');
	}
	
	public function get_content() {
		global $USER;
		
		if ($this->content !== null) {
			return $this->content;
		}
		$this->content =  new stdClass;
		$this->content->text='';
		
		
		$global_wsdl=get_config('fhl_evasys','wsdl');
		$wsdl  = (!empty($global_wsdl)) ? $global_wsdl : false;
		$global_header=get_config('fhl_evasys','header');
		$header  = (!empty($global_header)) ? $global_header : false;
		$global_soapuser=get_config('fhl_evasys','soapuser');
		$soapuser  = (!empty($global_soapuser)) ? $global_soapuser : false;
		$global_soappass=get_config('fhl_evasys','soappass');
		$soappass  = (!empty($global_soappass)) ? $global_soappass : false;
		$global_participation_url=get_config('fhl_evasys','participation_url');
		$participation_url  = (!empty($global_participation_url)) ? $global_participation_url : false;
		/*
		$global_proxy=get_config('fhl_evasys','proxy');
		$proxy  = (!empty($global_proxy)) ? $global_proxy : '';
		$global_proxyport=get_config('fhl_evasys','proxyport');
		$proxyport  = (!empty($global_proxyport)) ? $global_proxyport : 0;
		*/		
		$survey  = (!empty($this->config->survey)) ? $this->config->survey : false;
		if (($survey==0)||($survey=='')){$survey=false;}
		
		$blocktext='';
		if ((!$wsdl)||(!$header)||(!$soapuser)||(!$soappass)||(!$survey)) {
			if (has_capability('moodle/block:edit', $this->page->context)) {
				$blocktext.=get_string('warning_missing_configuration_error', 'block_fhl_evasys2');
			} else {
				$blocktext.='';	
			}
		} else {
			try {
				$evasys=new Evasys($wsdl, $header, $soapuser, $soappass);
			} catch (Exception $e) {
				$evasys=false;
			}

			if (!$evasys->client) {
				if (has_capability('moodle/block:edit', $this->page->context)) {
					$blocktext.=get_string('warning_evays_configuration_error', 'block_fhl_evasys2');
				} else {
					$blocktext.='';	
				}
			} else {
			
				$email=$USER->email;
				try {
					$teilnahmelink=$evasys->GetOnlineSurveyLinkByEmail($survey, $email);
				} catch (Exception $e) {

				}				
				if (!$teilnahmelink) {
					// allready voted or survey id not correct
					$blocktext.='';
				} else {
					if (!$participation_url) {
						$surveylink=$teilnahmelink;
					} else {
						$tanarray=explode('=', $teilnahmelink);
						$tan=$tanarray[1];
						$surveylink=$participation_url.$tan;
					}
					$introduction  = (!empty($this->config->introduction)) ? $this->config->introduction : get_string('default_introduction', 'block_fhl_evasys2');
					$linktext  = (!empty($this->config->linktext)) ? $this->config->linktext : get_string('default_linktext', 'block_fhl_evasys2');
					$description  = (!empty($this->config->description)) ? $this->config->description : get_string('default_description', 'block_fhl_evasys2');
		
					$blocktext.='<div class="introduction">'.$introduction.'</div>';
					$blocktext.='<div class="evaluationlink"><a href="'.$surveylink.'" target="_blank">'.$linktext.'</a></div>';
					$blocktext.='<div class="description">'.$description.'</div>';
					
				}
				
			}	
		}
		
		$this->content->text = $blocktext;
		$this->content->footer = '';
		
		return $this->content;  
	}
	
	public function specialization() {  
		if (!empty($this->config->title)) {    
			$this->title = $this->config->title;  
		} else {    
			$this->config->title = get_string('default_title', 'block_fhl_evasys2');
		}   
	}

	public function instance_allow_multiple() {
		return true;
	}	

	public function html_attributes() {    
		$attributes = parent::html_attributes(); 
		$attributes['class'] .= ' block_fhl_evasys2'; 
		return $attributes;
	}	

	public function applicable_formats() {  
		return array('course-view' => true);
	}	
	
	function has_config() {
		return true;
	}		
}    