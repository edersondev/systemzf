<?php

class Cgmi_Form_Element_InputGroups extends Cgmi_Form_Element_Text {

    public $helper = 'formText';
    
    protected $_sizing;
    protected $_appendAddon;
    protected $_prependAddon;
    
    protected $_btAppendAddon;
    protected $_btPrependAddon;

    public function init ()
    {
	$this->addFilters(array('StripTags','StringTrim'));
    }

    public function loadDefaultDecorators ()
    {
	if ( !$this->loadDefaultDecoratorsIsDisabled () )
	{
	    $decorators = $this->getDecorators ();
	    if ( empty ( $decorators ) )
	    {
		$this->addDecorator ( 'InputGroups' );
	    }
	}
	return $this;
    }

    /**
     * Opções: input-group-lg, input-group-sm
     * @param string $sizing
     */
    public function setSizing($sizing)
    {
        $this->_sizing = $sizing;
    }
    
    public function getSizing()
    {
        return $this->_sizing;
    }
    
    /**
     * Addon que ficará antes do input
     * @param string $value
     */
    public function setAppendAddon($value)
    {
        $this->_appendAddon = $value;
    }
    
    public function getAppendAddon()
    {
        return $this->_appendAddon;
    }
    
    /**
     * Addon que ficará depois do input
     * @param string $value
     */
    public function setPrependAddon($value)
    {
        $this->_prependAddon = $value;
    }
    
    public function getPrependAddon()
    {
        return $this->_prependAddon;
    }
    
    /**
     * Button que será exibido antes do input
     * O array deverá ter os seguites itens:
     * id: é o id do button
     * label: nome ou icon que será exibido. Ex.: Pesquisar ou <i><i class="glyphicon glyphicon-search"></i></i>
     * class (opcional): o padrão é a class default, mas pode ser primary, success, info, warning e danger
     * @param array $arrBtAddon
     */
    public function setBtAppendAddon(array $arrBtAddon)
    {
        $this->_btAppendAddon = $arrBtAddon;
    }
    
    public function getBtAppendAddon()
    {
        return $this->_btAppendAddon;
    }
    
    /**
     * Button que será exibido depois do input
     * O array deverá ter os seguites itens:
     * id: é o id do button
     * label: nome ou icon que será exibido. Ex.: Pesquisar ou <i><i class="glyphicon glyphicon-search"></i></i>
     * class (opcional): o padrão é a class default, mas pode ser primary, success, info, warning e danger
     * @param array $arrBtAddon
     */
    public function setBtPrependAddon(array $arrBtAddon)
    {
        $this->_btPrependAddon = $arrBtAddon;
    }
    
    public function getBtPrependAddon()
    {
        return $this->_btPrependAddon;
    }
}
