<?php

namespace CGExtensions\reports;

// a class defining the contents and formatting of a report
class formatted_report_defn extends report_defn
{
    private $_item_template;

    public function set_item_template($tpl)
    {
        $tpl = trim($tpl);
        if( $tpl ) $this->_item_template = $tpl;
    }

    public function get_item_template()
    {
        return $this->_item_template;
    }

} // end of class

?>