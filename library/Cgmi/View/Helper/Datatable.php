<?php

/**
 * Description of DataTables
 *
 * @author Ederson Ferreira <ederson.dev@gmail.com>
 */
class Cgmi_View_Helper_Datatable extends Zend_View_Helper_Abstract
{
    private $_header;
    private $_urlAjax;
    private $_options;
    private $_msgError;
    public $_showTableFooter = false;
    public $_idTable = 'dataTables-example';
    public $_pagesToCache = 3;
    public $_columnOrder = 0;
    public $_orderingDirection = 'desc';
    public $_select = 'false';
    
    /**
     * Possibilidades do pagingType
     * Paging Type = numbers || simple || simple_numbers || full || full_numbers
     */
    public $_pagingType = 'simple_numbers';
    
    private $_columnDefs = array();
    
    private $_classBt = 'default';
    
    public function datatable(array $arrHeader = array(), $urlAjax = '', array $options = array())
    {
        $this->_header = $arrHeader;
        $this->_urlAjax = $urlAjax;
        $this->_options = $options;
        
        $this->setTableOptions();
        
        // Carrega os arquivos
        $this->loadFiles();
        
        $xhtml = '';
        $this->checkBasicVariables();
        if ( empty($this->_msgError) ){
            $xhtml = $this->htmlTable();
        }
        
        return $xhtml;
    }
    
    private function setTableOptions()
    {
        if ( isset($this->_options['showTableFooter']) ){
            $this->_showTableFooter = boolval($this->_options['showTableFooter']);
        }
        if ( isset($this->_options['idTable']) ){
            $this->_idTable = $this->_options['idTable'];
        }
        if ( isset($this->_options['actionsBt']) ){
            $this->setIconsColumn();
        }
        if ( isset($this->_options['hiddenColumns']) ){
            $this->setHiddenColumn();
        }
        if ( isset($this->_options['columnOrder']) ){
            $this->_columnOrder = $this->_options['columnOrder'];
        }
        if ( isset($this->_options['pagesToCache']) ){
            $this->_pagesToCache = $this->_options['pagesToCache'];
        }
        if ( isset($this->_options['pagingType']) ){
            $this->_pagingType = $this->_options['pagingType'];
        }
        if ( isset($this->_options['orderingDirection']) ){
            $this->_orderingDirection = $this->_options['orderingDirection'];
        }
        if ( isset($this->_options['select']) ){
            $this->_select = $this->_options['select'];
        }
        if ( isset($this->_options['customContent']) ){
            $this->setCustomConten();
        }
    }
    
    private function loadFiles()
    {
        $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/components/datatables/media/js/jquery.dataTables.min.js');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/components/datatables-plugins/cache/pipelining.js');
        
        if ( $this->_select === 'true' ) {
            $this->view->headLink()->appendStylesheet($this->view->baseUrl().'/components/datatables-plugins/select/css/select.dataTables.min.css');
        }
    }
    
    private function checkBasicVariables()
    {
        if ( $this->_header && empty($this->_header) ){
            $this->_msgError = 'Informe o cabeçalho da tabela.';
        }
        if ( $this->_urlAjax && empty($this->_urlAjax) ){
            $this->_msgError = 'Informe a url para o processamento ajax.';
        }
    }
    
    private function htmlTable()
    {
        $header = $this->headerTable();
        $html = <<<EOF
            <div class="dataTable_wrapper">
                <table class="table table-striped table-bordered table-hover" id="{$this->_idTable}" width="100%">
                    {$header}
                    {$this->footerTable()}
                </table>
            </div>
EOF;
        $onloadJs = new Zend_Session_Namespace('onloadJs');
        $onloadJs->codeJs[] = $this->codeJs();
        
        return $html;
    }
    
    private function headerTable()
    {
        $htmlHeader = <<<EOF
            <thead>
                <tr>
                    {$this->columsTable()}
                </tr>
            </thead>
EOF;
        return $htmlHeader;
    }
    
    private function footerTable()
    {
        $htmlFooter = '';
        if ( $this->_showTableFooter ){
            $htmlFooter = <<<EOF
            <tfoot>
                <tr>
                    {$this->columsTable()}
                </tr>
            </tfoot>
EOF;
        }
        return $htmlFooter;
    }
    
    private function columsTable()
    {
        $arrItens = array();
        foreach ( $this->_header as $item ){
            $arrItens[] = "<th>$item</th>";
        }
        return implode(PHP_EOL, $arrItens);
    }
    
    private function setIconsColumn()
    {
        $buttons = $this->_options['actionsBt']['buttons'];
        $arrIcons = array();
        
        foreach ($buttons as $button) {
            $arrIcons[] = $this->createButtonLink($button);
            if ( !empty($button['url']) ){
                $this->_urlActionBt[] = "<input type=\"hidden\" name=\"url_{$button['idButton']}\" id=\"url_{$button['idButton']}\" value=\"{$button['url']}\" />";
            }
        }
        
        if ( !empty($arrIcons) ){
            $column = $this->_options['actionsBt']['column'];
            $targets = array_search($column, $this->_header);
            $strButtons = implode(' ', $arrIcons);
            $this->_columnDefs[] = <<<EOF
                {
                    "targets": [$targets],
                    "data": null,
                    "width": '10%',
                    "class": 'text-center',
                    "searchable": false,
                    "orderable": false,
                    "render": function ( data, type, full, meta ) {
                        return '{$strButtons}';
                    }
                }
EOF;
            
            $this->_columnOrder = $this->_columnOrder + 1;
            
//            $this->_actionJsBt = <<<EOF
//                var oTable = $('#$this->_idTable').DataTable();
//                $('#$this->_idTable').on( 'click', 'button', function () {
//                    var btaction = $(this).attr('btaction');
//                    var data = oTable.row( $(this).parents('tr') ).data();
//                    var redirectUrl = $('#url_' + btaction).val() + data[{$targets}];
//                    if ( btaction == 'delete' ){
//                        bootbox.confirm("MENSAGEM", function(result) {
//                            if (result){
//                                window.location.href = redirectUrl;
//                            }
//                        });
//                    } else {
//                        window.location.href = redirectUrl;
//                    }
//                });
//EOF;
        }
    }
    
    private function createButtonLink(array $arrOptions)
    {
        $classButton = "btn-{$this->_classBt}";
        if ( isset($arrOptions['classButton']) && !empty($arrOptions['classButton']) ) {
            $classButton = "btn-{$arrOptions['classButton']}";
        }
        
        if ( isset($arrOptions['sizeButton']) && !empty($arrOptions['sizeButton']) ) {
            $classButton .= " {$arrOptions['sizeButton']}";
        }

        $labelLink = '';
        $arrAttrBt = array();
        if ( isset($arrOptions['icon']) && !empty($arrOptions['icon']) ) {
            $labelLink = "<span class=\"{$arrOptions['icon']}\" aria-hidden=\"true\"></span> ";
        }
        
        if ( isset($arrOptions['textLink']) && !empty($arrOptions['textLink']) ) {
            $labelLink .= $arrOptions['textLink'];
        }
        
        if ( isset($arrOptions['title']) && !empty($arrOptions['title']) ) {
            $arrAttrBt[] = "title=\"{$arrOptions['title']}\"";
        }
        
        if ( isset($arrOptions['jscallback']) && !empty($arrOptions['jscallback']) ) {
            $arrAttrBt[] = "onclick= \"{$arrOptions['jscallback']}('+data[0]+')\"";
        }
        
        $attrButton = implode(' ', $arrAttrBt);
        
        $htmlBt = <<<EOF
<button type="button" class="btn {$classButton}" btaction="{$arrOptions['idButton']}" {$attrButton}>{$labelLink}</button>
EOF;
        if ( isset($arrOptions['url']) && !empty($arrOptions['url']) ) {
            $htmlBt = <<<EOF
<a class="btn {$classButton}" href="{$arrOptions['url']}'+data[0]+'" role="button" btaction="{$arrOptions['idButton']}" {$attrButton}>{$labelLink}</a>
EOF;
        }
                
        return $htmlBt;
    }
    
    private function setHiddenColumn()
    {
        foreach ( $this->_options['hiddenColumns'] as $column ){
            $targets = array_search($column, $this->_header);
            $this->_columnDefs[] = <<<EOF
{
    "targets": [ $targets ],
    "visible": false,
    "searchable": false
}
EOF;
        }
    }
    
    private function setCustomConten()
    {
        $column = $this->_options['customContent']['column'];
        $targets = array_search($column, $this->_header);
        $this->_columnDefs[] = <<<EOF
            {
                "targets": [$targets],
                "data": null,
                "searchable": false,
                "orderable": false,
                "defaultContent": {$this->_options['customContent']['content']}
            }
EOF;
    }
    
    private function codeJs()
    {
        $langFile = $this->view->baseUrl() . '/components/datatables-plugins/i18n/Portuguese-Brasil.json';
        $codeJs = <<<EOF
            $('#$this->_idTable').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "select": {$this->_select},
                "ajax": $.fn.dataTable.pipeline( {
                    url: '$this->_urlAjax',
                    pages: $this->_pagesToCache, // number of pages to cache
                    method: 'post',
                } ),
                "language": {
                    "url": "{$langFile}"
                },
                "pagingType": "{$this->_pagingType}",
                "order": [[ {$this->_columnOrder}, "{$this->_orderingDirection}" ]],
                "dom":
                    "<'row'<'col-sm-6'l><'col-sm-6'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-6'i><'col-sm-6'p>>",
                {$this->getJsoncolumnDefs()}
            });
EOF;
        return $codeJs;
    }
    
    private function getJsoncolumnDefs()
    {
        $strJson = '';
        if ( !empty($this->_columnDefs) ){
            $strJson = '"columnDefs"' . ':[' . implode(",", $this->_columnDefs) . ']';
        }
        return $strJson;
    }
}
