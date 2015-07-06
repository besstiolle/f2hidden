<?php

/**
 * This file defines the abstract report definition class.
 *
 * @package CGExtensions
 * @category Reports
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2014 by Robert Campbell
 */

namespace CGExtensions\reports;

/**
 * An abstrat class to define a report definition.
 */
abstract class report_defn
{
    /**
     * @ignore
     */
    private $_query;           // object of type query

    /**
     * @ignore
     */
    private $_rs;              // object of type resultset

    /**
     * @ignore
     */
    private $_title;           // string

    /**
     * @ignore
     */
    private $_desc;            // string

    /**
     * Set the query that will be used for this report.
     *
     * @param \CGExtensions\query\query $query The query object
     */
    public function set_query(\CGExtensions\query\query $query)
    {
        $this->_query = $query;
    }

    /**
     * Get the query object used for this report.
     *
     * @return \CGExtensions\query\query& The query object.
     */
    protected function &get_query()
    {
        return $this->_query;
    }

    /**
     * Get the resultset object that will be used to provide data for this report.
     *
     * @return \CGExtensions\query\resultset& The resultset object
     */
    public function &get_resultset()
    {
        if( !is_object($this->_rs) ) {
            if( !is_object($this->_query) ) throw new \LogicException('No query supplied to report definition');
            $this->_rs = $this->_query->execute();
            $this->_rs->MoveFirst();
        }
        return $this->_rs;
    }

    /**
     * Get the title of this report.
     *
     * @return string
     */
    public function get_title()
    {
        return $this->_title;
    }

    /**
     * Set the title of this report.
     *
     * @param string $str The title
     */
    public function set_title($str)
    {
        $this->_title = trim($str);
    }

    /**
     * Get the description of this report.
     *
     * @return string
     */
    public function get_description()
    {
        return $this->_desc;
    }

    /**
     * Set the description for this report
     *
     * @param string $str The description
     */
    public function set_description($str)
    {
        $this->_desc = trim($str);
    }

} // end of class

?>