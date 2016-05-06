<?php

class Cgmi_Form_Element_Select extends Zend_Form_Element_Multi {

    /**
     * 'multiple' attribute
     * @var string
     */
    public $multiple = false;

    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formSelect';

    public $_chosen = false;
    
    public $_chosenOptions = array(
        'disable_search_threshold' => 10, // desabilita a busca se tiver menos de 10 opções
        'no_results_text' => 'Nenhum resultado encontrado',
        'placeholder_text_single' => 'Selecione uma opção',
        'placeholder_text_multiple' => 'Selecione algumas opções',
        'allow_single_deselect' => true,
    );

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'Select' );
	    }
	}
	return $this;
    }
    
    public function setChosen($bool)
    {
        $this->_chosen = (bool)$bool;
    }
    
    public function setChosenOptions(array $options = null)
    {
        if ( !empty($options) ){
            $this->_chosenOptions = $this->_chosenOptions + $options;
        }
    }
    
    public function getChosenOptions()
    {
        return $this->_chosenOptions;
    }
    
    public function loadFilesChosen()
    {
        $view = Zend_Layout::getMvcInstance ()->getView ();
	$this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/chosen/js/chosen.jquery.js' );
	$this->getView ()->headLink ()->appendStylesheet ( $view->baseUrl () . '/components/chosen/css/chosen.css' );
    }
    
}
