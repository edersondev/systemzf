<?php

/**
 * Description of Panel
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */

class Cgmi_View_Helper_Panel extends Zend_View_Helper_Abstract
{
    public $_style = 'default';
    private $_collapse = false;
    private $_idCollapse = 'contentCollapse';
    private $_iconTitle;
    
    private function setOptions($options)
    {
        if ( isset($options['icon']) && !empty($options['icon']) ){
            $this->_iconTitle = $options['icon'];
        }
        if ( isset($options['collapse']) && !empty($options['collapse']) ){
            $this->_collapse = (boolval($options['collapse']));
        }
        if ( isset($options['idCollapse']) && !empty($options['idCollapse']) ){
            $this->_idCollapse = $options['idCollapse'];
        }
        if ( isset($options['style']) && !empty($options['style']) ){
            $this->_style = $options['style'];
        }
    }
    
    public function panel($body = null, $title = null, $footer = null, $options = array())
    {
        $this->setOptions($options);
        $xhtml = <<<EOF
            <div class="panel panel-{$this->_style}">
                {$this->getTitle($title)}
                {$this->getBody($body)}
                {$this->getFooter($footer)}
            </div>
EOF;
        return $xhtml;
    }
    
    private function getBody($body)
    {
        $xhtml = '';
        if ( !empty($body) ){
            if ( $this->_collapse ){
            $xhtml = <<<EOF
                <div id="{$this->_idCollapse}" class="panel-collapse collapse">
                    <div class="panel-body">
                        {$body}
                    </div>
                </div>
EOF;
            } else {
            $xhtml = <<<EOF
                <div class="panel-body">
                    {$body}
                </div>
EOF;
            }
        }
        return $xhtml;
    }
    
    private function getTitle($title)
    {
        $xhtml = '';
        if ( !empty($title) ){
            
            if ( $this->_collapse ){
                $xhtml = <<<EOF
                <div class="panel-heading">
                    <h4 class="panel-title">
                        {$this->getIconTitle()}
                        <a data-toggle="collapse" href="#{$this->_idCollapse}" aria-expanded="false" class="collapsed">
                            {$title}
                        </a>
                    </h4>
                </div>
EOF;
            } else {
                $xhtml = <<<EOF
                <div class="panel-heading">
                    <h4 class="panel-title">
                        {$this->getIconTitle()}
                        {$title}
                    </h4>
                </div>
EOF;
            }
            
        }
        return $xhtml;
    }
    
    private function getFooter($footer)
    {
        $xhtml = '';
        if ( !empty($footer) ){
            $xhtml = <<<EOF
                <div class="panel-footer">{$footer}</div>
EOF;
        }
        return $xhtml;
    }
    
    private function getIconTitle()
    {
        $xhtml = '';
        if ( !empty($this->_iconTitle) ){
            $xhtml = "<i class=\"{$this->_iconTitle}\"></i>";
        }
        return $xhtml;
    }
}
