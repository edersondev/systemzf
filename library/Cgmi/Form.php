<?php

class Cgmi_Form extends Zend_Form
{
    
    protected $_defaultDisplayGroupClass = 'Cgmi_Form_DisplayGroup';
    
    public $_labelwidth = 2;
    public $_inputwidth = 10;
    public $_jsvalidate = true;
    private $_hasValidator = false;
    
    public function __construct($options = null)
    {
        // Caso nao seja definido um id para o form será gerado um randômico
        $this->addAttribs(array('id' => $this->randStrGen(6) ));
        
        if ( isset($options['labelwidth']) && !empty($options['labelwidth']) ){
            $this->_labelwidth = $options['labelwidth'];
        }
        if ( isset($options['inputwidth']) && !empty($options['inputwidth']) ){
            $this->_inputwidth = $options['inputwidth'];
        }
        unset($options['labelwidth']);
        unset($options['inputwidth']);
        
        $this->addPrefixPath('Cgmi_Form_Decorator', 'Cgmi/Form/Decorator', 'decorator')
            ->addPrefixPath('Cgmi_Form_Element', 'Cgmi/Form/Element', 'element')
            ->addElementPrefixPath('Cgmi_Form_Decorator', 'Cgmi/Form/Decorator', 'decorator')
            ->addElementPrefixPath('Cgmi_Validate', 'Cgmi/Validate/', 'validate')
            ->addDisplayGroupPrefixPath('Cgmi_Form_Decorator', 'Cgmi/Form/Decorator');
        
        $view = Zend_Layout::getMvcInstance ()->getView ();
	$this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/maskjs/jquery.mask.min.js' );
        
        $this->getView ()->headLink()->appendStylesheet($view->baseUrl().'/components/formvalidation/css/formValidation.min.css');
        $this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/formvalidation/js/formValidation.min.js' );
        $this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/formvalidation/js/framework/bootstrap.min.js' );
        $this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/formvalidation/js/language/pt_BR.js' );
        
        parent::__construct($options);

    }
    
    /**
    * Load the default decorators
    *
    * @return Zend_Form
    */
   public function loadDefaultDecorators()
   {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
                return $this;
        }
        
        // Grupo usado para o form 'form-horizontal'
        $btAction = $this->getDisplayGroup('btAction');
        if ($btAction) {
            $btAction->clearDecorators();
            $btAction->addDecorator('FormElements')
                ->addDecorator(array( 'secondLayer' => 'HtmlTag' ), array( 'tag' => 'div', 'class' => "col-sm-offset-{$this->_labelwidth} col-sm-{$this->_inputwidth}" ))
                ->addDecorator(array( 'firstLayer' => 'HtmlTag' ), array( 'tag' => 'div', 'class' => 'form-group' ));
        }

        // class: text-right | text-center
        $btAlign = $this->getDisplayGroup('btAlign');
        if ( $btAlign ){
            $arrOptions = array('tag' => 'div');
            $groupClass = $btAlign->getAttrib('class');
            if ( $groupClass ){
                $arrOptions['class'] = $groupClass;
            }
            $btAlign->clearDecorators();
            $btAlign->addDecorator('FormElements')
                    ->addDecorator('HtmlTag', $arrOptions);
        }
        
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
                $this->addDecorator('FormElements')
                //->addDecorator('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form'))
                ->addDecorator('Form');
        }
        
        $classForm = $this->getAttrib('class');
        if ( $classForm === 'form-horizontal' ){
            foreach ($this->getElements() as $element){
                if (!$element instanceof Cgmi_Form_Element_Submit){
                    $element->setOptions(array(
                        'labelwidth' => $this->_labelwidth,
                        'inputwidth' => $this->_inputwidth
                    ));
                }
            }
        }

        if ( $this->_jsvalidate === true ) {
            $formID = $this->getId();
            foreach ( $this->getElements() as $element ){
                if (!$element instanceof Cgmi_Form_Element_Submit){
                    $validators = $element->getValidators();
                    $objValidator = new Cgmi_ValidatorJsForm($validators);
                    if ( $validators ){
                        $this->_hasValidator = true;
                        $elementValidators = $objValidator->getValidatorJs();
                        foreach ( $elementValidators as $validator ) {
                            $element->setAttribs($validator);
                        }
                    }
                }
            }
            
            if ( $this->_hasValidator ) {
                $this->setAttrib('data-fv-framework', 'bootstrap');
                $this->setAttrib('data-fv-locale', 'pt_BR');
                $jsValidateForm = "var zendfv_{$formID} = $('#{$formID}').formValidation();";
                $onloadJs = new Zend_Session_Namespace('onloadJs');
                $onloadJs->codeJs[] = $jsValidateForm;
            }
        }
        $this->setJsInput();
        
        return $this;
   }
   
   /**
    * Seta o javascript do campo input se necessário
    */
   private function setJsInput()
   {
       $formID = $formID = $this->getId();
        foreach ( $this->getElements() as $element ){
            switch ($element) {
                case $element instanceof Cgmi_Form_Element_Text:
                    $this->setInputMask($formID, $element);
                break;
                
                case $element instanceof Cgmi_Form_Element_Datepicker:
                    $this->setJsDatepicker($formID, $element);
                break;
            }
        }
   }
   
   private function setInputMask($formID, $element)
   {
       $onloadJs = new Zend_Session_Namespace('onloadJs');
       $maskInput = $element->getMaskInput();
        if ( !empty($maskInput) ){
            $fieldName = $element->getName();
            if ( $this->_jsvalidate === true && $this->_hasValidator === true ) {
                $onloadJs->codeJs[] = <<<EOF
zendfv_{$formID}.find('[name="{$fieldName}"]').mask('{$maskInput}', {clearIfNotMatch: true});
EOF;
            } else {
                $onloadJs->codeJs[] = <<<EOF
$('#{$element->getId()}').mask("$maskInput", {clearIfNotMatch: true});
EOF;
            }
        }
   }
   
   private function setJsDatepicker($formID, $element)
   {
       $onloadJs = new Zend_Session_Namespace('onloadJs');
       $jsonOptions = Zend_Json::encode($element->getDatepickerOptions());
       $fieldName = $element->getName();
       if ( $this->_jsvalidate === true && $this->_hasValidator === true ) {
           $onloadJs->codeJs[] = <<<EOF
$('#group-{$fieldName} .input-group.date').datepicker(
    $jsonOptions
).on("changeDate", function(e) {
    $('#{$formID}').formValidation('revalidateField', '{$fieldName}');
});
EOF;
       } else {
           $onloadJs->codeJs[] = <<<EOF
$('#group-{$element->getName()} .input-group.date').datepicker(
    $jsonOptions
);
EOF;
       }
   }
   
   private function randStrGen($len){
        $result = "";
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $charArray = str_split($chars);
        for($i = 0; $i < $len; $i++){
            $randItem = array_rand($charArray);
            $result .= "".$charArray[$randItem];
        }
        return $result;
    }
   
   /*
    * Função para popular elementos de listas
    */
   public function setItemList($name, $options, $default = null)
   {
       
       $elementType = $this->getElement($name)->getType();
       
       // Campos do tipo Select terão o primeiro option vazio com o nome "Selecione"
       if ($elementType == 'Cgmi_Form_Element_Select'){
           $startValue = array('' => 'Selecione');
           $values = $startValue + $options;
           $this->getElement($name)->setMultiOptions($values);
       } else {
           $this->getElement($name)->setMultiOptions($options);
       }
       
       if ($default){
           $this->getElement($name)->setValue($default);
       }
   }
}