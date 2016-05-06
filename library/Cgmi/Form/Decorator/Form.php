<?php

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Form.php';

class Cgmi_Form_Decorator_Form extends Zend_Form_Decorator_Form
{
	public function render($content)
	{
		$form    = $this->getElement();
		$view    = $form->getView();
		if (null === $view) {
			return $content;
		}
	
		$helper        = $this->getHelper();
		$attribs       = $this->getOptions();
		$name          = $form->getFullyQualifiedName();
		$attribs['id'] = $form->getId();
		$elements      = $form->getElements();
		
		return $view->$helper($name, $attribs, $content, $elements);
	}
}
