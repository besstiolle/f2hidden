<?php

namespace CGExtensions\reports;

class formatted_report_generator extends report_generator
{
    private $_report = array();
    private $_out;
    private $_doc_template;

    public function __construct(formatted_report_defn $rpt)
    {
        parent::__construct($rpt);

        $tmp = array();
        $tmp['title'] = $this->report()->get_title();
        $tmp['description'] = $this->report()->get_description();
        $tmp['generated'] = time();
        $tmp['items'] = array();
        $this->_report = $tmp;
    }

    public function set_template($tpl)
    {
        $this->_doc_template = trim($tpl);
    }

    protected function get_template()
    {
        return $this->_doc_template;
    }

    protected function process_template($tpl)
    {
        $smarty = cmsms()->GetSmarty();
        $actionmodule = $smarty->get_template_vars('actionmodule');
        if( $actionmodule ) {
            $mod = \cms_utils::get_module($actionmodule);
            if( is_object($mod) ) {
                if( endswith($tpl,'.tpl') ) {
                    $out = $mod->ProcessTemplate($tpl);
                }
                else {
                    $out = $mod->ProcessTemplateFromDatabase($tpl);
                }
            }
        }
        else {
            $out = $smarty->fetch('file:'.$tpl);
        }
        return $out;
    }

    protected function each_row($item)
    {
        $tpl = $this->report()->get_item_template();
        if( !$tpl ) return;

        $smarty = cmsms()->GetSmarty();
        $smarty->assign('item',$item);
        $this->_report['items'][] = $this->process_template($tpl);
    }

    public function generate()
    {
        $this->start();
        $rs = $this->report()->get_resultset();
        while( !$rs->EOF ) {
            $this->each_row($rs->get_object());
            $rs->MoveNext();
        }
        unset($rs);
        $this->finish();
    }

    public function get_output()
    {
        if( !count($this->_report['items']) ) return;

        $smarty = cmsms()->GetSmarty();
        $smarty->assign('report',$this->_report);
        return $this->process_template($this->get_template());
    }
} // end of class

?>