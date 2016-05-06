<?php

class Cgmi_Form_Element_Recaptcha extends Zend_Form_Element_Captcha
{
    
    public function __construct ( $spec, $options = array() )
    {
	$arrOptions = array( 'captcha' => array(
            'captcha' => 'reCaptcha',
            //'pubkey' => '', // para teste '6LcLovUSAAAAAFjHaFF7CiluZO_aoC1zhVg1dhTR'
            //'privkey' => '', // para teste '6LcLovUSAAAAAHPuPGjoE2aDncTSq2CH1o1Q1AF6'
            'lang' => 'pt-BR',
            'messages' => array(
                'badCaptcha' => 'Você digitou um valor inválido para o captcha.'
            )
	) );
        
        if ( $options ) {
            $arrOptions['captcha'] += $options;
        }
        
	parent::__construct ( $spec, $arrOptions );
    }

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'Recaptcha' );
	    }
	}
	return $this;
    }
    
}
