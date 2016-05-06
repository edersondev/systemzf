<?php

class Cgmi_Form_Element_CaptchaSum extends Cgmi_Form_Element{
    
    public $captchaImage;
    public $n1;
    public $n2;
    private $alphabet = array('K', 'g', 'A', 'D', 'R', 'V', 's', 'L', 'Q', 'w');
    private $alphabetsForNumbers = array(
            array('K', 'g', 'A', 'D', 'R', 'V', 's', 'L', 'Q', 'w'),
            array('M', 'R', 'o', 'F', 'd', 'X', 'z', 'a', 'K', 'L'),
            array('H', 'Q', 'O', 'T', 'A', 'B', 'C', 'D', 'e', 'F'),
            array('T', 'A', 'p', 'H', 'j', 'k', 'l', 'z', 'x', 'v'),
            array('f', 'b', 'P', 'q', 'w', 'e', 'K', 'N', 'M', 'V'),
            array('i', 'c', 'Z', 'x', 'W', 'E', 'g', 'h', 'n', 'm'),
            array('O', 'd', 'q', 'a', 'Z', 'X', 'C', 'b', 't', 'g'),
            array('p', 'E', 'J', 'k', 'L', 'A', 'S', 'Q', 'W', 'T'),
            array('f', 'W', 'C', 'G', 'j', 'I', 'O', 'P', 'Q', 'D'),
            array('A', 'g', 'n', 'm', 'd', 'w', 'u', 'y', 'x', 'r')
         );
    
    
    public function _init() {
        
        
        $images = glob("img/captcha/*.png");
        foreach($images as $image_to_delete)
        {
            unlink($image_to_delete);      
        }

        $this->n1 = rand(1, 9);
        $this->n2 = rand(1, 9);
    }
    
    function generateImage($text, $file) {
        $im = @imagecreate(190, 25) or die("Cannot Initialize new GD image stream");
        $background_color = imagecolorallocate($im, 223, 240, 216);
        $text_color = imagecolorallocate($im, 60, 118, 61);
    imagestring($im, 10, 60, 5,  $text, $text_color);
    imagepng($im, $file);
    imagedestroy($im);
    }

    public function getNumbers(){     
        $numbersSum = new Zend_Session_Namespace('numbersSum');
        $this->captchaImage = 'img/captcha/captcha'.time().'.png';
        $this->generateImage($this->n1.' + '.$this->n2.' =', $this->captchaImage); 
        return $this->captchaImage;
    }
    
        // converting alphabet character to a number
    function getIndex($alphabet, $letter) {
        for($i=0; $i<count($alphabet); $i++) {
            $l = $alphabet[$i];
            if($l === $letter) return $i;
        }
    }

    // getting the original expression's result
    function getExpressionResult($code) {
        $userAlphabetIndex = $this->getIndex($this->alphabet, substr($code, 0, 1));
        $number1 = (int) $this->getIndex($this->alphabetsForNumbers[$userAlphabetIndex], substr($code, 1, 1));
        $number2 = (int) $this->getIndex($this->alphabetsForNumbers[$userAlphabetIndex], substr($code, 2, 1));
        return $number1 + $number2;
    }

    
    public function code() { 

        $usedAlphabet = rand(1, 9);
        $code = $this->alphabet[$usedAlphabet].
                $this->alphabetsForNumbers[$usedAlphabet][$this->n1].
                $this->alphabetsForNumbers[$usedAlphabet][$this->n2];
        
        return $code;
    }

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'CaptchaSum' );
	    }
	}
	return $this;
    }
    
       public function isValid($value, $context = null)
   {
       $numbersSum = new Zend_Session_Namespace('numbersSum');
       if ( $value['value'] != $this->getExpressionResult($value['captchasum'])){
           $this->setErrors(array('Resultado errado.'));
           return false;
       } else {
           return true;
       }
   }

}


