<?php

class Cgmi_Form_Element extends Zend_Form_Element
{
    public function __construct($spec, $options = null)
    {
        $this->addPrefixPath('Cgmi_Form_Decorator', 'Cgmi/Form/Decorator', 'decorator');
        parent::__construct($spec, $options);
    }

    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }
        
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('InputBootstrap');
        }
        return $this;
    }
}
