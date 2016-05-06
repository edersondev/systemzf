<?php

class Cgmi_Controller_Action extends Zend_Controller_Action
{
    protected $_flashMessenger = null;
    protected $_redirector = null;
    
    const REGISTRO_SALVO = 'Registro salvo com sucesso';
    const REGISTRO_EXCLUIDO = 'Registro excluído com sucesso';
    const REGISTRO_ERRO = 'Erro ao gravar dados';
    const ENVIO_SUCESSO = 'Uma nova senha foi enviada para o seu email!';
    
    public function init()
    {
        parent::init();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->initView();
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }
    
    public function fomartCurrency($value)
    {
        $arrConfig = array(
            'locale' => 'pt_BR',
            'position' => Zend_Currency::LEFT,
            'symbol' => 'R$',
            'format' => '¤ #,##0.00'
        );
        $currency = new Zend_Currency($arrConfig);
        return $currency->toCurrency($value);
    }
    
    public function getPriceProduct($arrProduct)
    {
        return ( is_null($arrProduct['discount_price']) ? $arrProduct['price'] : $arrProduct['discount_price'] );
    }
    
    /**
     * Parametro from e name from estão setados no application.ini
     * @param array $params
     */
    public function sendMail($params)
    {
        $mail = new Zend_Mail('utf-8');
        
        $layoutEmail = $this->getLayoutEmail();
        $strMessageEmail = $params['body'] . $this->assinaturaEmail();
        $bodyMessage = strtr($layoutEmail, array("[mensagem_email]" => $strMessageEmail));
        
        if ( isset($params['typeBody']) && $params['typeBody'] === 'text' ) {
            $mail->setBodyText($params['body']);
        } else {
            $mail->setBodyHtml($bodyMessage); // Padrão
        }
        $mail->setSubject($params['subject']);
        $mail->addTo($params['mailTo']); // array chave nome, valor email
        
        try {
            $mail->send();
        } catch (Exception $exc) {
            //echo '<pre>';var_dump($exc);exit;
        }
    }
    
    public function assinaturaEmail()
    {
        $objModelConfiguration = new Application_Model_Configuration();
        $assinaturaEmail = $objModelConfiguration->getConfigValueByName('EMAIL_SIGNATURE');
        return $assinaturaEmail;
    }
    
    /**
     * Trata o array com a lista de estados que vem do banco para o campo select
     * @param array $arrStates
     * @return array
     */
    public function selectState($arrStates)
    {
        $list = array();
        if ( $arrStates ) {
            foreach ( $arrStates as $state ) {
                $list[$state['code']] = $state['name'];
            }
        }
        return $list;
    }
    
    public function getLayoutEmail()
    {
        $urlLogo = $this->view->serverUrl() . $this->view->baseUrl() . '/images/logo_duran.png';
        $ano = date('Y');
        
        $layout_email = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Duran Deals</title>
  <style type="text/css">
  body {margin: 0; padding: 0; min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%; max-width: 600px;}
  .header {padding: 20px 30px 10px 30px;}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .subhead {font-size: 15px; color: #000; font-family: sans-serif; letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643; font-family: sans-serif;}
  .h1 {font-size: 14px; line-height: 38px; font-weight: bold;}
  .h2 {padding: 0 0 15px 0; font-size: 24px; line-height: 28px; font-weight: bold;}
  .bodycopy {font-size: 16px; line-height: 22px;}
  table td {font-size: 14px; line-height: 22px;}
  .button {text-align: center; font-size: 18px; font-family: sans-serif; font-weight: bold; padding: 0 30px 0 30px;}
  .button a {color: #ffffff; text-decoration: none;}
  .footer {padding: 20px 30px 15px 10px;}
  .footercopy {font-family: sans-serif; font-size: 12px; color: #ffffff;}
  .footercopy a {color: #ffffff; text-decoration: underline;}

  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443; padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block; margin-top: 20px; padding: 10px 50px; background: #2f3942; border-radius: 5px; text-decoration: none!important; font-weight: bold;}
  }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="#f6f8f1">
<table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->     
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="header" style="border-bottom:1px solid #ccc;">
          <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">  
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img class="fix" src="{$urlLogo}" width="200" border="0" alt="" />
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 320px;text-align: center;">  
            <tr>
              <td height="70">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      Minoxidil
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      Excelente Produto Contra Queda de Cabelo
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding borderbottom">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="bodycopy">
                [mensagem_email]
              </td>
            </tr>
          </table>
        </td>
      </tr>
      
      <tr>
        <td class="footer" bgcolor="#44525f">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="footercopy" style="font-family: sans-serif; font-size: 12px; color: #ffffff;">
                <p>Copyright © {$ano} Duran Deals. Todos os direitos reservados.</p>
                
                <p>SRTVS 701. Conjunto D. Bloco A. Sala 417 - Asa Sul. Brasília / DF. CEP: 70340-907.<br />
CNPJ: 23.837.621/0001-20</p>
              </td>
            </tr>
            
          </table>
        </td>
      </tr>
    </table>
    <!--[if (gte mso 9)|(IE)]>
          </td>
        </tr>
    </table>
    <![endif]-->
    </td>
  </tr>
</table>

</body>
</html>
EOF;
        return $layout_email;
    }
}
