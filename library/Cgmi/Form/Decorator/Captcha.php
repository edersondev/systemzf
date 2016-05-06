<?php

class Cgmi_Form_Decorator_Captcha extends Zend_Form_Decorator_Captcha
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
        
        $view = $element->getView();

        $name = $element->getFullyQualifiedName();
        $hiddenName = $name . '[id]';
        $textName = $name . '[input]';
        
        $arrgetAttrbs = $element->getAttribs();
        $hiddenAttr = $element->getAttribs();
        if ( isset($arrgetAttrbs['labelwidth']) || isset($arrgetAttrbs['inputwidth']) ){
            unset($arrgetAttrbs['labelwidth']);
            unset($arrgetAttrbs['inputwidth']);
            unset($hiddenAttr['labelwidth']);
            unset($hiddenAttr['inputwidth']);
        }
        
        $arrAttrbs = array_merge($arrgetAttrbs,array('class' => 'form-control'));
        $text = $view->formText($textName, '', $arrAttrbs);
        $hidden = $view->formHidden($hiddenName, $element->getValue(), $hiddenAttr);
        
        return $text . $hidden;
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
    
    public function buildDivInput()
    {
        $element  = $this->getElement();
        
        $view = $element->getView();
        $captcha = $element->getCaptcha();
        $markup = $captcha->render($view, $element);
        
        $html = "<div>{$markup}</div>";
        $html .= $this->buildInput();
        $html .= $this->buildDescription();
        
        if ( $element->getInputButton() ){
            $html = <<<EOF
                <div>$markup</div>
                <div class="input-group">
                    {$this->buildInput()}
                    <span class="input-group-btn">
                        <button id="{$element->getIdButton()}" class="btn btn-default" type="button">
                            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                        </button>
                    </span>
                </div>
                {$this->buildDescription()}
EOF;
        }
        
        $inputwidth = $element->getAttrib('inputwidth');
        if ( $inputwidth ){
            $html = <<<EOF
                <div class="col-sm-{$inputwidth}">
                    {$html}
                </div>
EOF;
        }
        
        return $html;
    }
    
    public function buildElement()
    {
        $element  = $this->getElement();
        
        $view = $element->getView();
        $captcha = $element->getCaptcha();
        $markup = $captcha->render($view, $element);
        
        $htmlInput = "<div>{$markup}</div>";
        $htmlInput .= $this->buildInput();
        $htmlInput .= $this->buildDescription();
        $inputwidth = $element->getAttrib('inputwidth');
        if ( $inputwidth ){
            $htmlInput = <<<EOF
                <div class="col-sm-{$inputwidth}">
                    {$htmlInput}
                </div>
EOF;
        }
        
        $html = <<<EOF
        <div class="form-group" id="group-{$element->getName()}">
            {$this->buildLabel()}
            {$this->buildDivInput()}
        </div>
EOF;
        return $html;
    }
    
    public function render($content)
    {
        $element = $this->getElement();
        if (!method_exists($element, 'getCaptcha')) {
            return $content;
        }

        $view    = $element->getView();
        if (null === $view) {
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
                //return $content . $separator . $output;
                return $separator . $output;
        }
    }
}
