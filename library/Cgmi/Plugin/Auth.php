<?php

/**
 * Description of Auth
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */
class Cgmi_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Auth
     */
    protected $_auth = null;
    /**
     * @var Zend_Acl
     */
    protected $_acl = null;
    /**
     * @var array
     */
    
    protected $_notLoggedRoute = array(
        'controller' => 'autenticacao',
        'action'     => 'login',
        'module'     => 'default'
    );
    /**
     * @var array
     */
    protected $_forbiddenRoute = array(
        'controller' => 'error',
        'action'     => 'forbidden',
        'module'     => 'default'
    );

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = Zend_Registry::get('acl');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $getModule = $request->getModuleName();
        $getController = $request->getControllerName();
        $getAction = $request->getActionName();
        $resource = "{$getModule}-{$getController}";
        
        if ( $getModule === 'admin' ) {
            $controller = "";
            $action     = "";
            $module     = "";
            
            if ( $getController === 'autenticacao' ) {
                $controller = $request->getControllerName();
                $action     = $request->getActionName();
                $module     = $request->getModuleName();
            } else {
                $userData = $this->_auth->getIdentity();
                $session = new Zend_Session_Namespace( 'Zend_Auth' );
                $session->setExpirationSeconds( 3600 );
                
                if ( !$this->_auth->hasIdentity() ) {
                    
                    $controller = 'autenticacao';
                    $action     = 'index';
                    $module     = 'admin';
                } else if ( !$this->_isAuthorized($resource, $getAction, $userData->perfil_nome) ) {
                    $controller = $this->_forbiddenRoute['controller'];
                    $action     = $this->_forbiddenRoute['action'];
                    $module     = 'admin';
                } else {
                    $controller = $request->getControllerName();
                    $action     = $request->getActionName();
                    $module     = $request->getModuleName();
                }
            }
            
            $request->setControllerName($controller);
            $request->setActionName($action);
            $request->setModuleName($module);
        }
    }
    
    protected function _isAuthorized($resource, $action, $role = 'visitante')
    {
        $this->_acl = Zend_Registry::get('acl');
        if ( !$this->_acl->has( $resource ) || !$this->_acl->isAllowed( $role, $resource, $action ) ) {
            return false;
        }
        return true;
    }
}
