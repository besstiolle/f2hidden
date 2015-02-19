<?php

namespace CGExtensions\reports;

// this generator gets report data then passes it through a template.
// returns string
class template_report_generator extends data_report_generator
{
    private $_template;
    private $_data = array('structure'=>array());
    private $_rec;

    public function set_template($tpl)
    {
        $this->_template = trim($tpl);
    }

    protected function get_template()
    {
        return $this->_template;
    }

    protected function start()
    {
        // todo: throw an exception if there is no template
    }

    public function get_output()
    {
        $out = null;
        $data = parent::get_output();
        if( !is_array($data) ) return $out;

        $smarty = cmsms()->GetSmarty();
        $smarty->assign('report_data',$data);
        $actionmodule = $smarty->get_template_vars('actionmodule');
        if( $actionmodule ) {
            $mod = cms_utils::get_module($actionmodule);
            if( is_object($mod) ) {
                if( endswith($this->get_template(),'.tpl') ) {
                    $out = $mod->ProcessTemplate($this->get_template());
                }
                else {
                    $out = $mod->ProcessTemplateFromDatabase($this->get_template());
                }
            }
        }
        else {
            $out = $smarty->fetch('file:'.$template);
        }
        return $out;
    }
} // end of class

?>