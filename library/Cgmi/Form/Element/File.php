<?php

class Cgmi_Form_Element_File extends Zend_Form_Element_File {

    public $helper = 'formFile';

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{

	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'File' );
	    }
	}
	return $this;
    }

}
