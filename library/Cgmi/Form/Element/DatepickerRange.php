<?php

class Cgmi_Form_Element_DatepickerRange extends Cgmi_Form_Element {

    /**
     * Default form view helper to use for rendering
     * @var string
     */
    public $helper = 'formText';
    
    // Default options
    public $_options = array(
        'todayBtn' => 'linked',
        'language' => 'pt-BR',
        'format' => 'dd/mm/yyyy',
        'todayHighlight' => true,
        'autoclose' => true,
        'clearBtn' => true,
    );
    
    public function init ()
    {
	$view = Zend_Layout::getMvcInstance ()->getView ();
	$this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/datepicker/js/bootstrap-datepicker.js' );
	$this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/datepicker/js/bootstrap-datepicker.pt-BR.js' );
	$this->getView ()->headLink ()->appendStylesheet ( $view->baseUrl () . '/components/datepicker/css/datepicker.css' );

	$this->addFilters ( array( 'StripTags', 'StringTrim' ) );
    }

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'DatepickerRange' );
	    }
	}
	return $this;
    }
    
    public function setDatepickerOptions(array $options = null)
    {
        if ( !empty($options) ){
            $this->_options = $this->_options + $options;
        }
    }
    
    public function getDatepickerOptions()
    {
        return $this->_options;
    }
            

}
