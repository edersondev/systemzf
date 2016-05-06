<?php

class Cgmi_Form_Element_Textarea extends Cgmi_Form_Element
{
    public $helper = 'formTextarea';
    
    public function init() 
    {
        $this->addFilters(array('StripTags','StringTrim'));
    }
}

