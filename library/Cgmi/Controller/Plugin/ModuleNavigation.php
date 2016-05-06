<?php

/**
 * Carrega o menu de forma dinamica de acordo com o módulo acessado
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */
class Cgmi_Controller_Plugin_ModuleNavigation extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Auth
     */
    protected $_auth = null;
    /**
     * @var Zend_Acl
     */
    protected $_acl = null;
    
    protected $_module;


//    public function __construct()
//    {
//        $this->_auth = Zend_Auth::getInstance();
//        $this->_acl = Zend_Registry::get('acl');
//    }
    
    public function preDispatch (Zend_Controller_Request_Abstract $request)
    {
    	$moduleName = $request->getModuleName();
        $this->_module = ( empty($moduleName) ? 'default' : $moduleName );
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->initView();
        $view = $viewRenderer->view;

        if(file_exists(APPLICATION_PATH . '/configs/' . strtolower($this->_module) . '_navigation.xml')) {
            $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/' . strtolower($this->_module) . '_navigation.xml', 'nav');
            $navigation = new Zend_Navigation($config);
            $view->navigation($navigation)
                //->setAcl($this->_acl)
                //->setRole($this->_role)
                ->menu ()
		->setUlClass ( 'nav navbar-nav' );
        }
    }
}