<?php

class Cgmi_Form_Decorator_Checkbox extends Zend_Form_Decorator_Abstract
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
 
        if ( isset($arrgetAttrbs['labelwidth']) || isset($arrgetAttrbs['inputwidth']) ){
            unset($arrgetAttrbs['labelwidth']);
            unset($arrgetAttrbs['inputwidth']);
        }
        
        return $element->getView()->$helper(
                $this->getName(),
                $element->getValue(),
                $arrgetAttrbs,
                $element->options
        );
    }
    
    public function buildDescription()
    {
        $element = $this->getElement();
        $desc = $element->getDescription();
        $html = '';
        if ( !empty($desc) ){
            $html = <<<EOF
               <span id="helpBlock-{$element->getName()}" class="help-block">
                   {$desc}
                </span> 
EOF;
        }
        
        return $html;
    }
    
    public function buildElement()
    {
        $element = $this->getElement();
        
        $labelWidth = $element->getAttrib('labelwidth');
        $inputwidth = $element->getAttrib('inputwidth');
        
        if ( $inputwidth ){
            $xhtml = <<<EOF
                <div class="form-group">
                    <div class="col-sm-offset-{$labelWidth} col-sm-{$inputwidth}">
                        <div class="checkbox">
                            <label>
                              {$this->buildInput()} {$element->getLabel()}
                            </label>
                        </div>
                        {$this->buildDescription()}
                    </div>
                </div>
EOF;
        } else {
            $xhtml = <<<EOF
            <div class="checkbox">
                <label>
                  {$this->buildInput()} {$element->getLabel()}
                </label>
            </div>
            {$this->buildDescription()}
EOF;
        }
        
        $this->setJsCheckbox();
        return $xhtml;
    }
    
    private function setJsCheckbox()
    {
        $element = $this->getElement();
        
        $codejs = <<<EOF
            $("#{$element->getId()}").bootstrapSwitch({
                size:'{$element->getSize()}',
                onText: '{$element->getOnText()}',
                offText: '{$element->getOffText()}',
            });
EOF;
        
        $onloadJs = new Zend_Session_Namespace('onloadJs');
        $onloadJs->codeJs[] = $codejs;
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

        $output = $this->buildElement();
        
        switch ($placement) {
            case (self::PREPEND):
                return $output . $separator . $content;
            case (self::APPEND):
            default:
                return $content . $separator . $output;
        }
    }
}
