<?php

class Cgmi_Form_Decorator_Hidden extends Zend_Form_Decorator_Abstract
{
    
    public function getName()
    {
        if (null === ($element = $this->getElement())) {
            return '';
        }

        $name = $element->getName();

        if (!$element instanceof Zend_Form_Element) {
            return $name;
        }

        if (null !== ($belongsTo = $element->getBelongsTo())) {
            $name = $belongsTo . '['
            . $name
            . ']';
        }

        if ($element->isArray()) {
            $name .= '[]';
        }

        return $name;
    }
    
    public function buildInput()
    {
        $element = $this->getElement();
        $helper  = $element->helper;
        $arrgetAttrbs = $element->getAttribs();
        return $element->getView()->$helper(
                $this->getName(),
                $element->getValue(),
                $arrgetAttrbs,
                $element->options
        );
    }
    
    public function buildErrors()
    {
        $return = $this->buildInput();
        
        return $return;
    }
    
    public function render($content)
    {
        $element = $this->getElement();
        
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }
        
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $errors    = $this->buildErrors();

        $output = $errors;   
        
        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
