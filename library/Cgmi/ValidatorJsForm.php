<?php

/**
 * Description of ValidatorJsForm
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */
class Cgmi_ValidatorJsForm 
{
    private $_arrValidator = array();
    
    public function __construct($objValidators)
    {
        foreach ( $objValidators as $nameValidator => $objValidator ){
            $this->setOptionsValidator($nameValidator, $objValidator);           
        }
    }
    
    public function getValidatorJs()
    {
        return $this->_arrValidator;
    }
    
    private function setOptionsValidator($strValidator, $objValidator)
    {
        $messagesTemplate = $objValidator->getMessageTemplates();
        $translator = $objValidator->getTranslator();
        switch ( $strValidator ) {
            case 'Zend_Validate_Alnum':
                $whiteSpace = ( $objValidator->getAllowWhiteSpace() ? '\s' : '' );
                $messageValidator = $messagesTemplate[Zend_Validate_Alnum::INVALID];
                $this->_arrValidator['regexp'] = array(
                    'data-fv-regexp' => 'true',
                    'data-fv-regexp-regexp' => "^[a-zA-Z\d\{$whiteSpace}]+$",
                    'data-fv-regexp-message' => $this->getMessageValidator($translator, $messageValidator)
                );
            break;
        
            case 'Zend_Validate_Alpha':
                $whiteSpace = ( $objValidator->getAllowWhiteSpace() ? '\s' : '' );
                $messageValidator = $messagesTemplate[Zend_Validate_Alpha::INVALID];
                $this->_arrValidator['regexp'] = array(
                    'data-fv-regexp' => 'true',
                    'data-fv-regexp-regexp' => "^[a-zA-Z{$whiteSpace}]+$",
                    'data-fv-regexp-message' => $this->getMessageValidator($translator, $messageValidator)
                );
            break;
        
            case 'Zend_Validate_Between':
                $messageKey = ($objValidator->getInclusive() ? Zend_Validate_Between::NOT_BETWEEN : Zend_Validate_Between::NOT_BETWEEN_STRICT);
                $messageValidator = $messagesTemplate[$messageKey];
                $this->_arrValidator['between'] = array(
                    'data-fv-between-min' => $objValidator->getMin(),
                    'data-fv-between-max' => $objValidator->getMax(),
                    'data-fv-between-message' => $this->getMessageValidator($translator, $messageValidator)
                );
            break;
        
            case 'Zend_Validate_CreditCard':
                $messageValidator = $messagesTemplate[Zend_Validate_CreditCard::INVALID];
                $this->_arrValidator['creditcard'] = array(
                    'data-fv-creditcard' => 'true',
                    'data-fv-creditcard-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_Digits':
                $messageValidator = $messagesTemplate[Zend_Validate_Digits::INVALID];
                $this->_arrValidator['digits'] = array(
                    'data-fv-digits' => 'true',
                    'data-fv-digits-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_EmailAddress':
                $messageValidator = $messagesTemplate[Zend_Validate_EmailAddress::INVALID];
                $this->_arrValidator['emailaddress'] = array(
                    'data-fv-emailaddress' => 'true',
                    'data-fv-emailaddress-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_GreaterThan':
                $messageValidator = $messagesTemplate[Zend_Validate_GreaterThan::NOT_GREATER];
                $this->_arrValidator['greaterthan'] = array(
                    'data-fv-greaterthan' => 'true',
                    'data-fv-greaterthan-inclusive' => 'true',
                    'data-fv-greaterthan-message' => $this->getMessageValidator($translator, $messageValidator),
                    'data-fv-greaterthan-value' => $objValidator->getMin()
                );
            break;
        
            case 'Zend_Validate_Identical':
                $messageValidator = $messagesTemplate[Zend_Validate_Identical::NOT_SAME];
                $this->_arrValidator['identical'] = array(
                    'data-fv-identical' => 'true',
                    'data-fv-identical-field' => $objValidator->getToken(),
                    'data-fv-identical-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_Ip':
                $messageValidator = $messagesTemplate[Zend_Validate_Ip::INVALID];
                $this->_arrValidator['ip'] = array(
                    'data-fv-ip' => 'true',
                    'data-fv-ip-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_LessThan':
                $messageValidator = $messagesTemplate[Zend_Validate_LessThan::NOT_LESS];
                $this->_arrValidator['lessthan'] = array(
                    'data-fv-lessthan' => 'true',
                    'data-fv-lessthan-inclusive' => 'true',
                    'data-fv-lessthan-value' => $objValidator->getMax(),
                    'data-fv-lessthan-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_NotEmpty':
                $messageValidator = $messagesTemplate[Zend_Validate_NotEmpty::IS_EMPTY];
                $this->_arrValidator['notempty'] = array(
                    'required' => 'required',
                    'data-fv-notempty-message' => $this->getMessageValidator($translator, $messageValidator),
                );
            break;
        
            case 'Zend_Validate_StringLength':
                $messageValidator = $messagesTemplate[Zend_Validate_StringLength::INVALID];
                $this->_arrValidator['stringlength'] = array(
                    'minlength' => $objValidator->getMin(),
                    'maxlength' => $objValidator->getMax(),
                    'data-fv-stringlength-message' => $this->getMessageValidator($translator, $messageValidator)
                );
            break;
        
            case 'Cgmi_Validate_Cpf':
                $messageValidator = $messagesTemplate[Cgmi_Validate_Cpf::CPF_INVALIDO];
                $this->_arrValidator['id'] = array(
                    'data-fv-id' => 'true',
                    'data-fv-id-country' => 'BR',
                    'data-fv-id-message' => $this->getMessageValidator($translator, $messageValidator)
                );
            break;
        
            case 'Cgmi_Validate_Cnpj':
                $messageValidator = $messagesTemplate[Cgmi_Validate_Cnpj::CNPJ_INVALIDO];
                $this->_arrValidator['vat'] = array(
                    'data-fv-vat' => 'true',
                    'data-fv-vat-country' => 'BR',
                    'data-fv-vat-message' => $this->getMessageValidator($translator, $messageValidator)
                );
            break;
        }
        
    }
    
    private function getMessageValidator($objTranslator, $messageValidator)
    {
        $arrMessages = array();
        if ( $objTranslator ) {
            $arrMessages = $objTranslator->getMessages();
        }
        
        if ( isset($arrMessages[$messageValidator]) ){
            $message = $objTranslator->translate($messageValidator);
        } else {
            $message = $messageValidator;
        }
        return $message;
    }
    
}
