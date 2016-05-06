<?php

class Cgmi_Form_Decorator_Recaptcha extends Zend_Form_Decorator_Captcha
{    
    public function buildInput()
    {
        $element = $this->getElement();
        $view = $element->getView();
        $helper  = $element->helper;
        
        $id            = $element->getId();
        $name          = $element->getBelongsTo();
        $challengeName = empty($name) ? 'recaptcha_challenge_field' : $name . '[recaptcha_challenge_field]';
        $responseName  = empty($name) ? 'recaptcha_response_field'  : $name . '[recaptcha_response_field]';
        $challengeId   = $id . '-challenge';
        $responseId    = $id . '-response';
        
        $arrgetAttrbs = $element->getAttribs();
        $arrAttrbs = array_merge($arrgetAttrbs,array('class' => 'form-control'));
        if ( isset($arrgetAttrbs['labelwidth']) || isset($arrgetAttrbs['inputwidth']) ){
            unset($arrgetAttrbs['labelwidth']);
            unset($arrgetAttrbs['inputwidth']);
        }
        
        $hidden = $view->formHidden(array(
            'name'    => $challengeName,
            'attribs' => array('id' => $challengeId),
        ));
        $hidden .= $view->formHidden(array(
            'name'    => $responseName,
            'attribs' => array('id'   => $responseId),
        ));
        
        $input = $element->getView()->$helper(
                $responseName,
                $element->getValue(),
                $arrAttrbs,
                $element->options
        );
        return $hidden . $input;
    }
    
    public function buildElement()
    {
        $element  = $this->getElement();
        
        $htmlInput = $this->buildInput();
        $labelWidth = $element->getAttrib('labelwidth');
        $inputwidth = $element->getAttrib('inputwidth');
        
        if ( $inputwidth ){
            $html = <<<EOF
                <div class="form-group">
                    <div class="col-sm-offset-{$labelWidth} col-sm-{$inputwidth}">
                        <div class="captcha">
                            <div id="recaptcha_image"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-{$labelWidth} col-sm-{$inputwidth}">
                        {$this->buildLabel()}
                        <div class="input-group">
                            {$htmlInput}
                            {$this->buttonsRecaptcha()}
                        </div>
                    </div>
                </div>
EOF;
        } else {
            $html = <<<EOF
                <div class="form-group">
                    <div class="captcha">
                      <div id="recaptcha_image"></div>
                    </div>
                </div>
                <div class="form-group" id="group-{$element->getName()}">
                    {$this->buildLabel()}
                    <div class="input-group">
                        {$htmlInput}
                        {$this->buttonsRecaptcha()}
                    </div>
                </div>
EOF;
        }
        
        $html .= $this->jsRecaptcha();
        return $html;
        
    }
    
    public function buildLabel()
    {
        $html = <<<EOF
          <div class="recaptcha_only_if_image">Informe as palavras acima</div>
          <div class="recaptcha_only_if_audio">Informe os números que você escutou</div>
EOF;
        return $html;
    }
    
    public function buttonsRecaptcha()
    {
        $html = <<<EOF
            <a class="btn btn-default input-group-addon" href="javascript:Recaptcha.reload()"><span class="glyphicon glyphicon-refresh"></span></a>
            <a class="btn btn-default input-group-addon recaptcha_only_if_image" href="javascript:Recaptcha.switch_type('audio')"><span class="glyphicon glyphicon-volume-up"></span></a>
            <a class="btn btn-default input-group-addon recaptcha_only_if_audio" href="javascript:Recaptcha.switch_type('image')"><span class="glyphicon glyphicon-picture"></span></a>
            <a class="btn btn-default input-group-addon" href="javascript:Recaptcha.showhelp()"><span class="glyphicon glyphicon-info-sign"></span></a>
EOF;
        return $html;
    }
    
    public function jsRecaptcha()
    {
        $element  = $this->getElement();
        $serverUrl = 'http://www.google.com/recaptcha/api';
        $pubkey = $element->getCaptcha()->getPubkey();
        $jscode = <<<EOF
            <script>var RecaptchaOptions = {theme: 'custom', custom_theme_widget: 'recaptcha_widget'};</script>
            <script type="text/javascript" src="{$serverUrl}/challenge?k=' . $pubkey . '"></script>
            <noscript>
                <iframe src="{$serverUrl}/noscript?k=' . $pubkey  . '" height="300" width="500" frameborder="0"></iframe><br/>
                <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
            </noscript>
EOF;
        return $jscode;
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
