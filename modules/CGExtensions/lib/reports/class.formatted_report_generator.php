<?php

/**
 * This file defines the abstract report generator class.
 *
 * @package CGExtensions
 * @category Reports
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\reports;

/**
 * A class to generate a report using a special template for each row or item.
 */
class formatted_report_generator extends report_generator
{
    /**
     * @ignore
     */
    private $_report = array();

    /**
     * @ignore
     */
    private $_out;

    /**
     * @ignore
     */
    private $_doc_template;

    /**
     * Object constructor.
     *
     * @param formatted_report_defn $rpt
     */
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

    /**
     * Set the template to be used for formatting the document.
     * The document template is a smarty template that should at the minimum consist of a {foreach $items as $item}...{/foreach} loop.
     * Each item in the loop will be an HTML string representing each formatted item.
     *
     * @param string $tpl The template contents.
     */
    public function set_template($tpl)
    {
        $this->_doc_template = trim($tpl);
    }

    /**
     * Get the document template.
     *
     * @return string
     */
    protected function get_template()
    {
        return $this->_doc_template;
    }

    /**
     * Process the specififed template through smarty.
     * This method will attempt to find the current action module, and given that and the name of the template find the template contents.
     * if the template name ends with .tpl a module file template is assumed.  Otherwise, a module database template will be assumed.
     * If a module cannot be determined, then a file template is assumed, using the 'file' smarty resource.
     *
     * @param string $tpl The name of the template to process.
     */
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

    /**
     * A callback method for processing each row.
     * By default, this method gives the item/row to smarty and calls the item template specified in the report definition.
     *
     * @abstract
     * @param mixed $item The item to be processed.
     * @return void
     */
    protected function each_row($item)
    {
        $tpl = $this->report()->get_item_template();
        if( !$tpl ) return;

        $smarty = cmsms()->GetSmarty();
        $smarty->assign('item',$item);
        $this->_report['items'][] = $this->process_template($tpl);
    }

    /**
     * Generate the output of this report.
     *
     * @abstract
     * @return void
     */
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

    /**
     * Get the output of the report, after generation.
     *
     * @return mixed The output of this class depends upon the content of the report, and item templates passed to this object.
     */
    public function get_output()
    {
        if( !count($this->_report['items']) ) return;

        $smarty = cmsms()->GetSmarty();
        $smarty->assign('report',$this->_report);
        return $this->process_template($this->get_template());
    }
} // end of class

?>