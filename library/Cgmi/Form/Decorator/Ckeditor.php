<?php

class Cgmi_Form_Decorator_Ckeditor extends Zend_Form_Decorator_Abstract
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
        $html = "<div class=\"form-group\" id=\"group-{$element->getName()}\">";
        $inputwidth = $element->getAttrib('inputwidth');
        if ( $inputwidth ){
            $html .= <<<EOF
                {$this->buildLabel()}
                <div class="col-sm-{$inputwidth}">
                    {$this->buildInput()}
                    {$this->buildDescription()}
                </div>
EOF;
        } else {
            $html .= $this->buildLabel();
            $html .= $this->buildInput();
            $html .= $this->buildDescription();
        }
        $html .= "</div>";
        
        $this->buildJsCkeditor();
        
        return $html;
    }
    
    private function buildJsCkeditor()
    {
        $element = $this->getElement();
        
        $filebrowserUploadUrl = ($element->getUploadUrl() ? "config.filebrowserUploadUrl = '{$element->getUploadUrl()}'; \n" : '');
        $filebrowserBrowseUrl = ($element->getBrowseUrl() ? "config.filebrowserBrowseUrl = '{$element->getBrowseUrl()}'; \n" : '');
        
        $configToolBar = $element->getConfigToolBar();
        
        $codejs = <<<EOF
        var config = {
            language: 'pt-br',
            entities_latin: false,
            width: '{$element->getCkWidth()}'
        };

        {$filebrowserBrowseUrl}
        {$filebrowserUploadUrl}
        {$configToolBar}
        
        config.extraPlugins = 'filebrowser';
        
        CKEDITOR.replace( '{$element->getId()}', config );
        
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
