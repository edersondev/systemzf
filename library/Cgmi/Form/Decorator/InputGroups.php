<?php

class Cgmi_Form_Decorator_InputGroups extends Zend_Form_Decorator_Abstract
{
    
    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();
        if ($translator = $element->getTranslator()) {
                $label = $translator->translate($label);
        }
        
        $labelWidth = $element->getAttrib('labelwidth');
        $labelClass = array('class' => 'control-label');
        if ( $labelWidth ){
            $labelClass = array('class' => "col-sm-{$labelWidth} control-label");
        }
        
        return $element->getView()
        ->formLabel($element->getName(), $label, $labelClass);
    }
    
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
        $arrAttrbs = array_merge($arrgetAttrbs,array('class' => 'form-control'));
        
        if ( isset($arrAttrbs['labelwidth']) || isset($arrAttrbs['inputwidth']) ){
            unset($arrAttrbs['labelwidth']);
            unset($arrAttrbs['inputwidth']);
        }
 
        return $element->getView()->$helper(
                $this->getName(),
                $element->getValue(),
                $arrAttrbs,
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
        $element  = $this->getElement();
        
        $buttonDisabled = '';
        if (in_array('disabled', $element->getAttribs())){
            $buttonDisabled = ' disabled';
        }
        
        $html = "<div class=\"form-group\" id=\"group-{$element->getName()}\">";
        $inputwidth = $element->getAttrib('inputwidth');
        $sizing = ($element->getSizing() ? " {$element->getSizing()}" : '');
        
        if ( $inputwidth ){
            $html .= <<<EOF
                {$this->buildLabel()}
                <div class="col-sm-{$inputwidth}">
                    <div class="input-group{$sizing}">
                        {$this->appendAddon()}{$this->btAppendAddon()}
                        {$this->buildInput()}
                        {$this->prependAddon()}{$this->btPrependAddon()}
                    </div>
                    {$this->buildDescription()}
                </div>
EOF;
        } else {
            $html .= <<<EOF
                {$this->buildLabel()}
                <div class="input-group{$sizing}">
                    {$this->appendAddon()}{$this->btAppendAddon()}
                    {$this->buildInput()}
                    {$this->prependAddon()}{$this->btPrependAddon()}
                </div>
                {$this->buildDescription()}
EOF;
        }
        $html .= "</div>";
        return $html;
    }
    
    public function appendAddon()
    {
        $element  = $this->getElement();
        $html = '';
        $strAddon = $element->getAppendAddon();
        if ( $strAddon ) {
            $html = "<span class=\"input-group-addon\">{$strAddon}</span>";
        }
        return $html;
    }
    
    public function prependAddon()
    {
        $element  = $this->getElement();
        $html = '';
        $strAddon = $element->getPrependAddon();
        if ( $strAddon ) {
            $html = "<span class=\"input-group-addon\">{$strAddon}</span>";
        }
        return $html;
    }
    
    public function btAppendAddon()
    {
        $element  = $this->getElement();
        $html = '';
        $arrBt = $element->getBtAppendAddon();
        if ( $arrBt ) {
            $buttonDisabled = '';
            if (in_array('disabled', $element->getAttribs())){
                $buttonDisabled = ' disabled';
            }
            $btClass = (isset($arrBt['class']) ? $arrBt['class'] : 'default');
            $html = <<<EOF
            <span class="input-group-btn">
                <button class="btn btn-{$btClass}{$buttonDisabled}" type="button" id="{$arrBt['id']}">
                    {$arrBt['label']}
                </button>
            </span>
EOF;
        }
        return $html;
    }
    
    public function btPrependAddon()
    {
        $element  = $this->getElement();
        $html = '';
        $arrBt = $element->getBtPrependAddon();
        if ( $arrBt ) {
            $buttonDisabled = '';
            if (in_array('disabled', $element->getAttribs())){
                $buttonDisabled = ' disabled';
            }
            $btClass = (isset($arrBt['class']) ? $arrBt['class'] : 'default');
            $html = <<<EOF
            <span class="input-group-btn">
                <button class="btn btn-{$btClass}{$buttonDisabled}" type="button" id="{$arrBt['id']}">
                    {$arrBt['label']}
                </button>
            </span>
EOF;
        }
        return $html;
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
