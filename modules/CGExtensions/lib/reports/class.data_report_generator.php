<?php

/**
 * This file defines the data report generator class.
 *
 * @package CGExtensions
 * @category Reports
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\reports;

/**
 * This class defines a report generator that will output an array of data rather than any formatted output.
 * footers and headers can still be used to gather totals, etc.
 */
class data_report_generator extends tabular_report_generator
{
    /**
     * @ignore
     */
    private $_data = array('structure'=>array());

    /**
     * @ignore
     */
    private $_rec;

    /**
     * @ignore
     */
    protected function start()
    {
        $this->_data['title'] = $this->report()->get_title();
        $this->_data['description'] = $this->report()->get_description();
        $this->_data['generated'] = time();
    }

    /**
     * @ignore
     */
    protected function before_group_headers()
    {
        $this->_rec = array('headers'=>array(),'footers'=>array(),'body'=>array());
    }

    /**
     * @ignore
     */
    protected function do_group_header(tabular_report_defn_group $grp)
    {
        // this method does not call the parent method
        $lines = $grp->get_header_lines();
        if( count($lines) ) {
            foreach( $lines as $line ) {
                $rec = array();
                foreach( $this->report()->get_columns() as $key => $col ) {
                    $val = $line->get_column_value($key);
                    $rec[$key] = $this->get_group_column_display_value($key,$grp->get_column(),$val);
                }
                $this->_rec['headers'][] = $rec;
            }
        }
    }

    /**
     * @ignore
     */
    function do_group_footer(tabular_report_defn_group $grp)
    {
        // this method does not call the parent method
        $lines = $grp->get_footer_lines();
        if( count($lines) ) {
            foreach( $lines as $line ) {
                $rec = array();
                foreach( $this->report()->get_columns() as $key => $col ) {
                    $val = $line->get_column_value($key);
                    $rec[$key] = $this->get_group_column_display_value($key,$grp->get_column(),$val);
                }
                $this->_rec['footers'][] = $rec;
            }
        }
        // this group has changed, go through all columns and reset this group
        foreach( $this->report()->get_columns() as $key => $col ) {
            $col->reset_group($grp->get_column());
        }
    }

    /**
     * @ignore
     */
    function after_group_footers()
    {
        $this->_data['structure'][] = $this->_rec;
        $this->_rec = null;
    }

    /**
     * @ignore
     */
    protected function set_row($row)
    {
        parent::set_row($row);
        $this->_rec['body'][] = $row;
    }

    /**
     * @ignore
     */
    protected function draw_cell(tabular_report_cellfmt $col,$val)
    {
        // nothing to do here.
    }

    /**
     * Get the output data from this report.
     *
     * @return array
     */
    public function get_output()
    {
        return $this->_data;
    }
} // end of class

?>