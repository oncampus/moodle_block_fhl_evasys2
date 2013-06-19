<?php 
class block_fhl_evasys2_edit_form extends block_edit_form {     
	protected function specific_definition($mform) {         
		     
		$mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));    

    	$mform->addElement('text', 'config_title', get_string('configlabel_title', 'block_fhl_evasys2'));    
    	$mform->setDefault('config_title', get_string('default_title', 'block_fhl_evasys2'));    
    	$mform->setType('config_title', PARAM_TEXT);		

    	
    	if (!has_capability('block/fhl_evasys2:editsurvey', $this->page->context)) {
    		$survey_edit=array('readonly'=>'readonly');
    	} else {
			$survey_edit=array();    		
    	}
    	
		$mform->addElement('text', 'config_survey', get_string('configlabel_survey', 'block_fhl_evasys2'),$survey_edit);        
		$mform->setDefault('config_survey', get_string('default_survey', 'block_fhl_evasys2'));        
		$mform->setType('config_survey', PARAM_INT);
    	
    	
		$mform->addElement('textarea', 'config_introduction', get_string('configlabel_introduction', 'block_fhl_evasys2'),array('rows' => 5, 'cols' => 41));        
		$mform->setDefault('config_introduction', get_string('default_introduction', 'block_fhl_evasys2'));        
		$mform->setType('config_introduction', PARAM_CLEANHTML);
		
		$mform->addElement('text', 'config_linktext', get_string('configlabel_linktext', 'block_fhl_evasys2'),array('size' => 41));        
		$mform->setDefault('config_linktext', get_string('default_linktext', 'block_fhl_evasys2'));        
		$mform->setType('config_linktext', PARAM_TEXT);

		$mform->addElement('textarea', 'config_description', get_string('configlabel_description', 'block_fhl_evasys2'),array('rows' => 5, 'cols' => 41));        
		$mform->setDefault('config_description', get_string('default_description', 'block_fhl_evasys2'));        
		$mform->setType('config_description', PARAM_CLEANHTML);		
		
		$mform->addElement('html', get_string('notice_survey', 'block_fhl_evasys2'));
		  
	}
}