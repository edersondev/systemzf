<?php

class Cgmi_Form_Element_Text extends Cgmi_Form_Element
{
    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formText';
    
    public $maskinput;
    
    public function init()
    {
        $this->addFilters(array('StripTags','StringTrim'));
    }
    
    public function setMaskInput($str)
    {
        $this->maskinput = $str;
    }
    
    public function getMaskInput()
    {
        return $this->maskinput;
    }
}
