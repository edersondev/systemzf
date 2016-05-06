<?php

/**
 * Description of Setup
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */
class Cgmi_Acl_Setup
{
    protected $_acl;
    
    public function __construct()
    {
        $this->_acl = new Zend_Acl();
        $this->_initialize();
    }
    
    protected function _initialize()
    {
        $this->_setupRoles();
        $this->_setupResources();
        $this->_setupPrivileges();
        $this->_saveAcl();
    }
    
    protected function _setupRoles()
    {
        $this->_acl->addRole( new Zend_Acl_Role('visitante') );
        $this->_acl->addRole( new Zend_Acl_Role('cliente'), 'visitante' );
        $this->_acl->addRole( new Zend_Acl_Role('admin'), 'cliente' );
    }
    
    protected function _setupResources()
    {
        // Módulo padrão
        $this->_acl->addResource( new Zend_Acl_Resource('default-autenticacao') );
        $this->_acl->addResource( new Zend_Acl_Resource('default-comprar') );
        $this->_acl->addResource( new Zend_Acl_Resource('default-customer') );
        $this->_acl->addResource( new Zend_Acl_Resource('default-error') );
        $this->_acl->addResource( new Zend_Acl_Resource('default-index') );
        $this->_acl->addResource( new Zend_Acl_Resource('default-minha-conta') );
        
        // Módulo admin
        $this->_acl->addResource( new Zend_Acl_Resource('admin-autenticacao') );
        $this->_acl->addResource( new Zend_Acl_Resource('admin-cliente') );
        $this->_acl->addResource( new Zend_Acl_Resource('admin-index') );
        $this->_acl->addResource( new Zend_Acl_Resource('admin-pedido') );
        $this->_acl->addResource( new Zend_Acl_Resource('admin-produto') );
        $this->_acl->addResource( new Zend_Acl_Resource('admin-user') );
        $this->_acl->addResource( new Zend_Acl_Resource('admin-status-pedido') );
    }
    
    protected function _setupPrivileges()
    {
        $this->_acl->allow( 'visitante', 'default-autenticacao' )
            ->allow( 'visitante', 'default-comprar', $this->getListAllowActionsVisitante() )
            ->allow( 'visitante', 'default-customer' )
            ->allow( 'visitante', 'default-error' )
            ->allow( 'visitante', 'default-index' )
            ->allow( 'visitante', 'admin-autenticacao' );
        
        $this->_acl->allow( 'cliente', 'default-comprar', array('checkout-order', 'json-finish-order') )
            ->allow( 'cliente', 'default-minha-conta' );
        
        $this->_acl->allow( 'admin', 'admin-cliente' )
            ->allow( 'admin', 'admin-index' )
            ->allow( 'admin', 'admin-pedido' )
            ->allow( 'admin', 'admin-produto' )
            ->allow( 'admin', 'admin-user' )
            ->allow( 'admin', 'admin-status-pedido' );
    }

    private function getListAllowActionsVisitante()
    {
        $arrPrivileges = array(
            'index',
            'shopping-cart',
            'json-store-product',
            'json-store-shipping',
            'json-check-cpf',
            'json-check-email'
        );
        return $arrPrivileges;
    }
    
    protected function _saveAcl()
    {
        $registry = Zend_Registry::getInstance();
        $registry->set('acl', $this->_acl);
    }
}
