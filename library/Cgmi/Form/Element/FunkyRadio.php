<?php

class Cgmi_Form_Element_FunkyRadio extends Zend_Form_Element_Multi {

    public $helper = 'formRadio';
    public $arrItens = array();
    public $currency = 'pt_BR';
    public $defaultValue;
    
    public function init ()
    {
        $view = Zend_Layout::getMvcInstance ()->getView ();
        $this->getView ()->headLink ()->appendStylesheet ( $view->baseUrl () . '/css/funkyradio.css' );
    }
    
    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'FunkyRadio' );
	    }
	}
	return $this;
    }
    
    public function setArrItens(array $arrItens)
    {
        $this->arrItens = $arrItens;
    }
    
    public function getArrItens()
    {
        return $this->arrItens;
    }
    
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }
    
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
    
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
}
