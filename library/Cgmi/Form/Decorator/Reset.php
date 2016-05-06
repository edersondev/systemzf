<?php

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/ViewHelper.php';

class Cgmi_Form_Decorator_Reset extends Zend_Form_Decorator_ViewHelper  {

    public function render ( $content )
    {
	$this->getElement ()->setValue ( '' );
	return parent::render ( $content );
    }

}
