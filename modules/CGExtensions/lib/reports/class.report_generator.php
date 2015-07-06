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
 * An abstract class for a report generator.
 */
abstract class report_generator
{
    /**
     * @ignore
     * @var \CGExtensions\reports\report_defn $_report_defn A reference to the report definition.
     */
    private $_report_defn; // \CG\report_defn;

    /**
     * Constructor.
     *
     * @param \CGExtensions\reports\report_defn $report_defn The report definition.
     */
    public function __construct(report_defn $rpt)
    {
        $this->_report_defn = $rpt;
    }

    /**
     * Get the report definition.
     *
     * @return \CGExtensions\reports\report_defn
     */
    protected function report()
    {
        return $this->_report_defn;
    }

    /**
     * A callback function when the report is started.
     *
     * @abstract
     * @return void
     */
    protected function start() {}

    /**
     * A callback function for when the report is finished.
     *
     * @abstract
     * @return void
     */
    protected function finish() {}

    /**
     * A callback functon for each data row.
     *
     * @abstract
     * @param array $row The row returned from the query.
     */
    abstract protected function each_row($row);

    /**
     * Generate the report output.
     *
     * @return void
     */
    public function generate()
    {
        $this->start();
        $rs = $this->report()->get_resultset();
        while( !$rs->EOF() ) {
            $this->each_row($rs->fields);
            $rs->MoveNext();
        }
        unset($rs);
        $this->finish();
    }

    /**
     * Get the generated output.
     * This method may return textual data suitable for echoing/displaying.  Or it it may generate a static file and return nothing.
     *
     * @abstract
     * @return mixed
     */
    abstract public function get_output();
} // end of class

?>