<?php

namespace CGExtensions\reports;

// this generator outputs text content with fixed column widths from the report definition.
class text_report_generator extends tabular_report_generator
{
    private $_col_width = 15;
    private $_out;

    public function set_column_width($val)
    {
        $this->_col_width = max(1,min(200,(int)$val));
    }

    protected function get_column_width()
    {
        return $this->_col_width;
    }

    protected function start()
    {
        parent::start();
    }

    protected function finish()
    {
        parent::finish();
    }

    protected function after_line()
    {
        parent::after_line();
        $this->_out .= "\n";
    }

    protected function after_group_footers()
    {
        parent::after_group_footers();
        $this->_out .= "\n";
    }

    protected function draw_cell(tabular_report_cellfmt $col,$val)
    {
        $this->_out .= str_pad($val,$this->get_column_width(),' ',STR_PAD_LEFT);
    }

    public function get_output()
    {
        return $this->_out;
    }
} // end of class

?>