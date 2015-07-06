<?php

/**
 * This file contains the abstract resultset class.
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2014 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * An abstract class to query the database and manage the results.
 */
abstract class base_resultset
{
    /**
     * The current filter object.
     */
    protected $_filter;

    /**
     * The total number of records matching the query (independent of limit and offset)
     */
    protected $_totalmatching;

    /**
     * The constructor
     *
     * @param query $query
     */
    public function __construct(query $query)
    {
        $this->_filter = $query;
    }

    /**
     * Return the query object
     *
     * @return query
     */
    public function &get_query()
    {
        return $this->_filter;
    }

    /**
     * Use the data from the query object, perform the database query and set the recordset member.
     *
     * This method should first see if the recordset has been set and not repeat the query... for the same of optimal behavior.
     */
    abstract protected function _query();

    /**
     * Get the number of records returned in this recordset.
     *
     * @return int
     */
    abstract public function RecordCount();

    /**
     * Move the pointer to the next matching row in the recordset.
     */
    abstract public function MoveNext();

    /**
     * Move the pointer  to the first matching row in the recordset.
     */
    abstract public function MoveFirst();

    /**
     * Move the pointer to the first matching row in the recordset.
     */
    abstract public function Rewind();

    /**
     * Move the pointer to the last matching row in the recordset.
     */
    abstract public function MoveLast();

    /**
     * Test if the pointer is at the end of the recordset (there are no more records)
     *
     * @return bool
     */
    abstract public function EOF();

    /**
     * Close the recordset, and free resources.
     */
    abstract public function Close();

    /**
     * return the total number of matches (independent of limit and offset)
     *
     * @return int
     */
    abstract public function TotalMatches();

    /**
     * Get an object representing the data at the current pointer position
     *
     * @return object
     */
    abstract public function &get_object();

    /**
     * Get a pagination object for this query and resultset
     *
     * @return pagination
     */
    abstract public function &get_pagination();

    /**
     * Fetch all of the records in this resultset as an array of objects.
     *
     * @return object[]
     */
    abstract public function FetchAll();
} // end of class

?>