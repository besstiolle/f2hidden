<?php

namespace CGExtensions\reports;

// a class defining the contents and formatting of a report
class tabular_report_defn extends report_defn
{
    private $_groups;          // array of tabular_report_defn_group objects
    private $_columns;         // hash of column key, and tabular_report_defn_column objects
    private $_content_columns; // array of column keys

    public function get_resultset()
    {
        $rs = $this->get_query()->execute();
        if( !$this->_columns && !$rs->EOF() ) {
            // auto add column definitions if none defined
            $cols = array_keys($rs->fields);
            foreach( $cols as $one ) {
                $colobj = new report_defn_column($one,ucwords($one),'{$val}');
                $this->define_column($colobj);
            }
        }
        // initialize the content columns
        if( !is_array($this->_content_columns)) $this->_content_columns = array_keys($this->_columns);
        return $rs;
    }

    public function get_groups()
    {
        return $this->_groups;
    }

    public function set_content_columns(array $line)
    {
        $this->_content_columns = $line;
    }

    public function get_content_columns()
    {
        return $this->_content_columns;
    }

    public function define_column(tabular_report_defn_column $col)
    {
        $key = $col->get_key();
        $this->_columns[$key] = $col;
    }

    public function get_columns()
    {
        return $this->_columns;
    }

    public function get_column($key)
    {
        if( array_key_exists($key,$this->_columns) ) return $this->_columns[$key];
    }

    public function add_group(tabular_report_defn_group $grp)
    {
        $this->_groups[] = $grp;
    }

} // end of class

// defines how to draw a cell in a tabular report
class tabular_report_cellfmt
{
    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';
    const ALIGN_CENTER = 'center';

    private $_key;
    private $_template;
    private $_align; // null, left, right
    private $_span = 1;

    public function __construct($key,$tpl = '{$val}',$align = self::ALIGN_LEFT,$span = 1)
    {
        // don't set a default for fmt as 'null' is valid when used in a header.
        $this->_key = trim($key); // todo: test this.
        $this->_template = trim($tpl);

        switch( $align ) {
        case self::ALIGN_LEFT:
            break;
        case self::ALIGN_RIGHT:
        case self::ALIGN_CENTER:
            break;
        default:
            $align = self::ALIGN_LEFT;
            break;
        }
        $this->_align = $align;
        $this->_span = max(1,(int)$span);
    }

    public function get_key()
    {
        return $this->_key;
    }

    public function get_template()
    {
        return $this->_template;
    }

    public function get_alignment()
    {
        return $this->_align;
    }

    public function get_span()
    {
        return $this->_span;
    }
}

// defines a column in a report_defn
class tabular_report_defn_column extends tabular_report_cellfmt
{
    private $_global_values; // array: all values for this column for the entire report
    private $_group_values; // hash of group column and totals.
    private $_label;

    public function __construct($key,$label,$fmt = '{$val}',$align = self::ALIGN_LEFT)
    {
        parent::__construct($key,$fmt,$align);
        $this->_label = $label;
        $this->_global_values = array();
        $this->_group_values = array();
    }

    public function get_label()
    {
        return $this->_label;
    }

    public function add_history_value($val)
    {
        $this->_global_values[] = $val;
    }

    public function add_group_history_value($grp_key,$val)
    {
        if( $grp_key ) {
            if( !isset($this->_group_values[$grp_key]) ) $this->_group_values[$grp_key] = array();
            $this->_group_values[$grp_key][] = $val;
        }
    }

    public function changed($val)
    {
        $cnt = count($this->_global_values);
        if( $cnt == 0 ) return TRUE; // no history
        $last = $this->_global_values[$cnt-1];
        return (bool)($last != $val);
    }

    public function reset_group($grp_key)
    {
        $this->_group_values[$grp_key] = array();
    }

    public function get_count()
    {
        return count($this->_global_values);
    }

    public function get_min()
    {
        $min = null;
        foreach( $this->_global_values as $val ) {
            if( $min == null || $val < $min ) $min = $val;
        }
        return $min;
    }

    public function get_max()
    {
        $max = null;
        foreach( $this->_global_values as $val ) {
            if( $max == null || $val > $max ) $max = $val;
        }
        return $max;
    }

    public function get_sum()
    {
        $sum = 0;
        foreach( $this->_global_values as $val ) {
            $sum += $val;
        }
        return $sum;
    }

    public function get_mean()
    {
        if( $this->get_count() == 0 ) return 0;
        return $this->get_sum() / $this->get_count();
    }

    public function get_median()
    {
        if( $this->get_count() == 0 ) return 0;
        $tmp = $this->_global_values;
        sort($tmp);
        $idx = (int) ($this->get_count() / 2);
        return $tmp[$idx];
    }

    protected function get_grp_values($grp_key)
    {
        $grp_key = trim($grp_key);
        if( !$grp_key ) return;
        if( isset($this->_group_values[$grp_key]) ) return $this->_group_values[$grp_key];
    }

    public function get_grp_count($grp_key)
    {
        $vals = $this->get_grp_values($grp_key);
        if( !is_array($vals) || count($vals) == 0 ) return;
        return count($vals);
    }

    public function get_grp_min($grp_key)
    {
        $vals = $this->get_grp_values($grp_key);
        if( !is_array($vals) || count($vals) == 0 ) return;

        $min = null;
        foreach( $vals as $one ) {
            if( $min == null || $one < $min ) $min = $one;
        }
        return $min;
    }

    public function get_grp_max($grp_key)
    {
        $vals = $this->get_grp_values($grp_key);
        if( !is_array($vals) || count($vals) == 0 ) return;

        $max = null;
        foreach( $vals as $one ) {
            if( $max == null || $one > $max ) $max = $one;
        }
        return $max;
    }

    public function get_grp_sum($grp_key)
    {
        $vals = $this->get_grp_values($grp_key);
        if( !is_array($vals) || count($vals) == 0 ) return;

        $sum = 0;
        foreach( $vals as $one ) {
            $sum += $one;
        }
        return $sum;
    }

    public function get_grp_mean($grp_key)
    {
        $count = $this->get_grp_count($grp_key);
        $sum = $this->get_grp_sum($grp_key);
        if( $count > 0 ) return $sum / $count;
    }

    public function get_grp_median($grp_key)
    {
        $vals = $this->get_grp_values($grp_key);
        if( !is_array($vals) || count($vals) == 0 ) return;

        $idx = (int) count($vals) / 2;
        sort($vals);
        return $vals[$idx];
    }
}

// defines a grouping in a report_defn
class tabular_report_defn_group
{
    const ACT_PAGE = '__PAGE__';
    const ACT_LINE = '__LINE__';

    private $_old_value;
    private $_column;
    private $_header_lines;
    private $_footer_lines;
    private $_after_action;
    private $_before_action;

    public function __construct($col)
    {
        $this->_column = trim($col);
    }

    public function get_column()
    {
        return $this->_column;
    }

    public function set_column($str)
    {
        $str = trim($str);
        if( $str ) $this->_column = $col;
    }

    public function get_header_lines()
    {
        return $this->_header_lines;
    }

    public function add_header_line(tabular_report_defn_group_line $line)
    {
        $this->_header_lines[] = $line;
    }

    public function get_footer_lines()
    {
        return $this->_footer_lines;
    }

    public function add_footer_line(tabular_report_defn_group_line $line)
    {
        $this->_footer_lines[] = $line;
    }

    public function set_after_action($tmp)
    {
        switch( strtolower($tmp) ) {
        case self::ACT_PAGE:
        case self::ACT_LINE:
            $this->_after_action = $tmp;
            break;
        }
    }

    public function get_after_action()
    {
        return $this->_after_action;
    }

    public function set_before_action($tmp)
    {
        switch( strtolower($tmp) ) {
        case self::ACT_PAGE:
        case self::ACT_LINE:
            $this->_before_action = $tmp;
            break;
        }
    }

    public function get_before_action()
    {
        return $this->_after_action;
    }

}

// defines a header or footer line in a report defn group
class tabular_report_defn_group_line
{
    private $_columns;

    public function __construct($hash)
    {
        foreach( $hash as $key => $tmp ) {
            if( (is_string($tmp) && $tmp !== '') || is_a($tmp,'tabular_report_cellfmt')) {
                // treat the value as a template for display.
                // here we should get the column from the report
                // and just adjust it's template.
                $tpl = new tabular_report_cellfmt($key,$tmp);
                $this->_columns[$key] = $tpl;
                continue;
            }
            $this->_columns[$key] = $tmp;
        }
    }

    public function get_cell_format($key)
    {
        if( array_key_exists($key,$this->_columns) ) return $this->_columns[$key];
    }
}
?>