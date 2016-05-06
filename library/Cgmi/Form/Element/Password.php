<?php

class Cgmi_Form_Element_Password extends Zend_Form_Element_Password {

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'InputBootstrap' );
	    }
	}
	return $this;
    }

}
