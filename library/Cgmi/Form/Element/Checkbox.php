<?php

class Cgmi_Form_Element_Checkbox extends Zend_Form_Element_Checkbox
{

    /**
     * null, 'mini', 'small', 'normal', 'large'
     * @var string
     */
    public $_size = 'small';
    public $_onText = 'Sim';
    public $_offText = 'Não';
    
    public function init()
    {
        $view = Zend_Layout::getMvcInstance ()->getView ();
        $this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/bootstrap-switch/js/bootstrap-switch.min.js' );
	$this->getView ()->headLink ()->appendStylesheet ( $view->baseUrl () . '/components/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css' );
    }
    
    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{

	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'Checkbox' );
	    }
	}
	return $this;
    }

    public function getSize()
    {
        return $this->_size;
    }
    
    public function setSize($size)
    {
        $this->_size = $size;
    }
    
    public function getOnText()
    {
        return $this->_onText;
    }
    
    public function setOnText($ontext)
    {
        $this->_onText = $ontext;
    }
    
    public function getOffText()
    {
        return $this->_offText;
    }
    
    public function setOffText($offtext)
    {
        $this->_offText = $offtext;
    }
    
}
