<?php

class Cgmi_Form_Element_MultiCheckbox extends Zend_Form_Element_Multi
{
    public $helper = 'formMultiCheckbox';
    protected $_registerInArrayValidator = false;
    
    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'MultiCheckbox' );
	    }
	}
	return $this;
    }
}
