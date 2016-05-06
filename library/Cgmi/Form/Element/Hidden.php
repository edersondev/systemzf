<?php

class Cgmi_Form_Element_Hidden extends Cgmi_Form_Element {

    /**
     * Use formHidden view helper by default
     * @var string
     */
    public $helper = 'formHidden';

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'Hidden' );
	    }
	}
	return $this;
    }

}
