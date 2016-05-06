<?php

/**
 * Description of FunkyRadio
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */
class Cgmi_Form_Decorator_FunkyRadio extends Zend_Form_Decorator_Abstract
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
    
    public function buildElement()
    {
        $element  = $this->getElement();
        $arrItens = $element->getArrItens();
        $radioValue = $element->getDefaultValue();
        
        $itens = array();
        foreach ( $arrItens as $item ) {
            $checked = ( $radioValue == $item['id'] ? 'checked' : '' );
            $lines = ( isset($item['lines']) ? $this->printLines($item['lines']) : '' );
            $itens[] = <<<EOF
                <div class="funkyradio-primary">
                    <input type="radio" name="{$this->getName()}" value="{$item['id']}" id="product-{$item['id']}" {$checked}  />
                    <label for="product-{$item['id']}">
                        {$item['name']}
                    </label>
                </div>
                {$lines}
EOF;
        }
        $htmlItens = implode(PHP_EOL, $itens);
        $html = "<div class=\"funkyradio\">{$htmlItens}</div>";
        return $html;
    }
    
    private function printLines($lines)
    {
        $html = array();
        foreach ( $lines as $line ) {
            $html[] = "<p>$line</p>";
        }
        return implode(PHP_EOL, $html);
    }
    
    private function fomartCurrency($value)
    {
        $element  = $this->getElement();
        $locale = $element->getCurrency();
        $currency = new Zend_Currency($locale);
        return $currency->toCurrency($value);
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
