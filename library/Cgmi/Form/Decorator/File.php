<?php

class Cgmi_Form_Decorator_File extends Zend_Form_Decorator_Abstract implements Zend_Form_Decorator_Marker_File_Interface
{
    /**
     * Attributes that should not be passed to helper
     * @var array
     */
    protected $_attribBlacklist = array('helper', 'placement', 'separator', 'value');

    /**
     * Default placement: append
     * @var string
     */
    protected $_placement = 'APPEND';

    /**
     * Get attributes to pass to file helper
     *
     * @return array
     */
    public function getAttribs()
    {
        $attribs   = $this->getOptions();

        if (null !== ($element = $this->getElement())) {
            $attribs = array_merge($attribs, $element->getAttribs());
        }

        foreach ($this->_attribBlacklist as $key) {
            if (array_key_exists($key, $attribs)) {
                unset($attribs[$key]);
            }
        }

        return $attribs;
    }

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
    
    public function buildInput()
    {
        $element  = $this->getElement();
        $view = $element->getView();
        $name      = $element->getName();
        $attribs   = $this->getAttribs();
        
        if ( isset($attribs['labelwidth']) || isset($attribs['inputwidth']) ){
            unset($attribs['labelwidth']);
            unset($attribs['inputwidth']);
        }
        
        if (!array_key_exists('id', $attribs)) {
            $attribs['id'] = $name;
        }
        
        $separator = $this->getSeparator();
        
        $markup    = array();
        $size      = $element->getMaxFileSize();
        if ($size > 0) {
            $element->setMaxFileSize(0);
            $markup[] = $view->formHidden('MAX_FILE_SIZE', $size);
        }

        if (Zend_File_Transfer_Adapter_Http::isApcAvailable()) {
            $markup[] = $view->formHidden(ini_get('apc.rfc1867_name'), uniqid(), array('id' => 'progress_key'));
        } else if (Zend_File_Transfer_Adapter_Http::isUploadProgressAvailable()) {
            $markup[] = $view->formHidden('UPLOAD_IDENTIFIER', uniqid(), array('id' => 'progress_key'));
        }

        $helper = $element->helper;
        if ($element->isArray()) {
            $name .= "[]";
            $count = $element->getMultiFile();
            for ($i = 0; $i < $count; ++$i) {
                $htmlAttribs        = $attribs;
                $htmlAttribs['id'] .= '-' . $i;
                $markup[] = $view->$helper($name, $htmlAttribs);
            }
        } else {
            $markup[] = $view->$helper($name, $attribs);
        }

        return implode($separator, $markup);
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
        return $html;
    }
    
    /**
     * Render a form file
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        
        $output    = $this->buildElement();

        switch ($placement) {
            case self::PREPEND:
                return $output . $separator . $content;
            case self::APPEND:
            default:
                return $content . $separator . $output;
        }
    }
}

