<?php

namespace CGExtensions\reports;

abstract class report_generator
{
    protected $_report_defn; // \CG\report_defn;

    public function __construct(report_defn $rpt)
    {
        $this->_report_defn = $rpt;
    }

    protected function report()
    {
        return $this->_report_defn;
    }

    protected function start() {}
    protected function finish() {}
    abstract protected function each_row($row);

    public function generate()
    {
        $this->start();
        $rs = $this->report()->get_resultset();
        while( !$rs->EOF ) {
            $this->each_row($rs->fields);
            $rs->MoveNext();
        }
        unset($rs);
        $this->finish();
    }

    abstract public function get_output();
} // end of class

?>