<?php

class Cgmi_Form_DisplayGroup extends Zend_Form_DisplayGroup {

    /**
     * Load default decorators
     * 
     * @return void
     */
    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'FormElements' )
			->addDecorator ( 'HtmlTag', array( 'tag' => 'div' ) )
			->addDecorator ( 'Fieldset' );
	    }
	}
    }

}
