<?php

class Cgmi_Form_Element_Captcha extends Zend_Form_Element_Captcha
{
    public $_inputButton = false;
    public $_idButton = 'refreshCap';
    
    public function __construct ( $spec, $options = array() )
    {
	$view = Zend_Layout::getMvcInstance ()->getView ();
	$arrOptions = array( 'captcha' => array(
		'captcha' => 'Image',
		'wordLen' => 5,
		'timeout' => 300,
		'fontsize' => 30,
		'font' => APPLICATION_PATH . '/../public/fonts/Verdana.ttf',
		'imgDir' => APPLICATION_PATH . '/../public/images/captcha/',
		'imgUrl' => $view->baseUrl () . '/images/captcha/',
		'ImgAlt' => 'Captcha',
		'ImgAlign' => 'left',
		'width' => '200',
		'height' => '69',
		'dotNoiseLevel' => 20,
		'lineNoiseLevel' => 2,
                'messages' => array(
                    'badCaptcha' => 'Você digitou um valor inválido para o captcha.'
                )
	) );
        $optMerge = array_merge($options, $arrOptions);
	parent::__construct ( $spec, $optMerge );
    }

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'Captcha' );
	    }
	}
	return $this;
    }

    /**
     * Exemplo de como atualizar a imagem por ajax:
     * http://stackoverflow.com/questions/5741753/how-to-reload-zend-captcha-image-on-click-refresh-button
     * 
     * @param bool $flag
     */
    public function setInputButton($flag)
    {
        $this->_inputButton = (bool)$flag;
    }
    
    public function getInputButton()
    {
        return $this->_inputButton;
    }
    
    public function setIdButton($str)
    {
        $this->_idButton = $str;
    }
    
    public function getIdButton()
    {
        return $this->_idButton;
    }
}
