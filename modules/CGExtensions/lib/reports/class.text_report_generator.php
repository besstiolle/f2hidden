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
 * This class will take a report definition and output a text file representation of the report.
 */
class text_report_generator extends tabular_report_generator
{
    /**
     * @ignore
     */
    private $_col_width = 15;

    /**
     * @ignore
     */
    private $_out;

    /**
     * Set the width of each column (in characters)
     *
     * @param int $val
     */
    public function set_column_width($val)
    {
        $this->_col_width = max(1,min(200,(int)$val));
    }

    /**
     * Get the column width (in characters)
     *
     * @return int
     */
    protected function get_column_width()
    {
        return $this->_col_width;
    }

    /**
     * @ignore
     */
    protected function start()
    {
        parent::start();
    }

    /**
     * @ignore
     */
    protected function finish()
    {
        parent::finish();
    }

    /**
     * @ignore
     */
    protected function after_line()
    {
        parent::after_line();
        $this->_out .= "\n";
    }

    /**
     * @ignore
     */
    protected function after_group_footers()
    {
        parent::after_group_footers();
        $this->_out .= "\n";
    }

    /**
     * @ignore
     */
    protected function draw_cell(tabular_report_cellfmt $col,$val)
    {
        $this->_out .= str_pad($val,$this->get_column_width(),' ',STR_PAD_LEFT);
    }

    /**
     * Get the output of the report.
     *
     * @return string Output suitable for saving to a text file.
     */
    public function get_output()
    {
        return $this->_out;
    }
} // end of class

?>