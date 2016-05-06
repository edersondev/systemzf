<?php

/**
 * Description of Ckeditor
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 * @link http://docs.ckeditor.com/#!/guide/dev_file_browser_api Maiores informações de como configurar
 * as actions de upload e browse.
 */
class Cgmi_Form_Element_Ckeditor extends Cgmi_Form_Element
{
    public $helper = 'formTextarea';
    
    /**
     * Number: 850 pixels wide OU String: '75%'
     * Default: '100%'
     * @var String/Number
     */
    public $_ckWidth = '100%';
    
    public $_uploadUrl;
    public $_browseUrl;
    
    /**
     * Para novas configurações acesse:
     * /public/components/ckeditor/samples/toolbarconfigurator/index.html#basic
     * @var Heredoc 
     */
    public $_configToolBar = <<<EOF
        config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'NewPage,Save,Scayt,Form,Radio,Checkbox,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Language,Flash,Smiley,Font';
EOF;
    
    public function init()
    {
        $view = Zend_Layout::getMvcInstance ()->getView ();
	$this->getView ()->headScript ()->appendFile ( $view->baseUrl () . '/components/ckeditor/ckeditor.js' );
    }
    
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }
        
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('Ckeditor');
        }
        return $this;
    }
    
    public function setCkWidth($width)
    {
        $this->_ckWidth = $width;
    }
    
    public function getCkWidth()
    {
        return $this->_ckWidth;
    }
    
    public function setUploadUrl($uploadUrl)
    {
        $this->_uploadUrl = $uploadUrl;
    }
    
    public function getUploadUrl()
    {
        return $this->_uploadUrl;
    }
    
    public function setBrowseUrl($browseUrl)
    {
        $this->_browseUrl = $browseUrl;
    }
    
    public function getBrowseUrl()
    {
        return $this->_browseUrl;
    }
    
    public function setConfigToolBar($strConfig)
    {
        $this->_configToolBar = $strConfig;
    }
    
    public function getConfigToolBar()
    {
        return $this->_configToolBar;
    }
    
}

/**
 * Essa é uma action de exemplo para a ação de upload de imagem do Ckeditor
 */

//public function uploadFileAction()
//{
//    $this->_helper->layout()->disableLayout();
//    $this->getHelper('viewRenderer')->setNoRender(true);
//
//    $view = Zend_Layout::getMvcInstance()->getView();
//
//    if ( $this->_request->isPost() ) {
//        try {
//            $adapter = new Zend_File_Transfer_Adapter_Http();
//            $adapter->setDestination(APPLICATION_PATH . '/../public/upload');
//
//            $adapter->addValidator('Count', false, array('max' => 1))
//            ->addValidator('Size', false, array('max' => '2MB'))
//            ->addValidator('Extension', false, array('jpg', 'png', 'case' => true));
//
//            if ( $adapter->isValid() ) {
//                $files = $adapter->getFileInfo();
//                foreach($files as $fileinfo) {
//                    if ( $adapter->isUploaded($fileinfo['name']) ) {
//                        $adapter->receive($fileinfo['name']);
//                    }
//                }
//                $urlSchemeHost = $this->getRequest()->getScheme() . '://' . $this->getRequest()->getHttpHost();
//                $urlImage = $urlSchemeHost . $view->baseUrl() . '/upload/' . $fileinfo['name'];
//                $funcNum = filter_input(INPUT_GET, 'CKEditorFuncNum');
//                $message = '';
//                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$urlImage', '$message');</script>";
//            } else {
//                foreach ($adapter->getMessages() as $msg) {
//                    $jsonMsg = json_encode($msg);
//                    echo "<script type='text/javascript'>alert({$jsonMsg});window.close();</script>";
//                }
//            }
//        } catch (Exception $exc) {
//            echo $exc->getTraceAsString();
//        }
//    }        
//}