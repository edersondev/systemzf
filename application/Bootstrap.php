<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView()
    {
        //Initialize view
        $view = new Zend_View();
        return $view;
    }
    
    protected function _initDocType()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->doctype('HTML5');
    }
    
    protected function _initClearOnloadJs()
    {
        $onloadJs = new Zend_Session_Namespace('onloadJs');
        unset($onloadJs->codeJs);
    }

    protected function _initPlaceholders()
    {
        // Titulo da página
        $this->bootstrap('View');
        $view = $this->getResource('View');
        $view->headTitle('Sistema em Zend Framework')
             ->setSeparator(' :: ');
        
        // Meta tags
        $view->headMeta()
            ->setCharset('UTF-8')
            ->headMeta()->appendHttpEquiv('X-UA-Compatible', 'IE=edge')
            ->appendName('viewport', 'width=device-width, initial-scale=1')
            ->appendName('description', 'Sistema base feito em Zend Framework 1 com Bootstrap 3')
            ->appendName('author', 'ederson.dev@gmail.com')
            ->appendName('keywords', 'php, zend framework, bootstrap3');
        
        $baseUrl = $this->bootstrap('frontController')->getResource('frontController')->getbaseUrl();
        
        // Carrega arquivos css
        $view->headLink()->prependStylesheet($baseUrl . 'components/bootstrap/dist/css/bootstrap.min.css');
        
        // Carrega os arquivos js
        $view->headScript()->prependFile($baseUrl .'components/bootbox/bootbox.js');
        $view->headScript()->prependFile($baseUrl .'components/bootstrap/dist/js/bootstrap.min.js');
        $view->headScript()->prependFile($baseUrl .'components/jquery/jquery.min.js');
    }
    
    public function _initLoader ()
    {
        $loader = Zend_Loader_Autoloader::getInstance ();
        $loader->setFallbackAutoloader ( true );
	$loader->registerNamespace ( 'Cgmi_' );
    }
   
   /**
    * _initHelpers
    *
    * @desc Sets alternative ways to helpers
    */
    protected function _initHelpers()
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->initView();

        // add zend view helper path
        $viewRenderer->view->addHelperPath('Cgmi/View/Helper/', 'Cgmi_View_Helper_');

        // add zend action helper path
        Zend_Controller_Action_HelperBroker::addPath('Cgmi/Controller/Helper/');
    }
    
    protected function _initAcl()
    {
        //$aclSetup = new Cgmi_Acl_Setup();
    }
    
    protected function _initPlugins()
    {
        $this->bootstrap("FrontController");
        $this->frontController->registerPlugin(new Cgmi_Controller_Plugin_ModuleNavigation());
        $this->frontController->registerPlugin(new Cgmi_Controller_Plugin_ErrorControllerSwitcher());
        //$this->frontController->registerPlugin(new Cgmi_Plugin_Auth());
    }
}

