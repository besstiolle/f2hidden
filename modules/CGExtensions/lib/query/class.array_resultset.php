<?php

/**
 * This file contains the array_resultset class.
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2014 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * The array resultset class simulates a resultset object from a flat array.
 * This is useful for using an array of loaded data in the reporting classes.
 *
 * This class does not support a limit or pagination.
 */
class array_resultset extends base_resultset
{
    private $_pos = 0;

    /**
     * Construct a new array_resultset from an array_query.
     *
     * @param array_query $query
     */
	public function __construct(array_query $query)
	{
		$this->_filter = $query->data();
	}

    /**
     * @ignore
     */
    protected function _query()
    {
        // do nothing
    }

    /**
     * Return the number of records in the array.
     *
     * @return int
     */
    public function RecordCount()
    {
        return count($this->_filter);
    }

    /**
     * Return the number of records in the array.
     *
     * @return int
     */
    public function TotalMatches()
    {
        return count($this->_filter);
    }

    /**
     * Move to the next record in the result set.
     */
    public function MoveNext()
    {
        $this->_pos++;
    }

    /**
     * Move to the first record in the result set.
     */
    public function MoveFirst()
    {
        $this->_pos=0;
    }

    /**
     * Move to the first record in the result set.
     *
     * @see MoveFirst()
     */
    public function Rewind()
    {
        $this->_pos=0;
    }

    /**
     * Move to the last record in the result set.
     */
    public function MoveLast()
    {
        $this->_pos = $this->RecordCount() - 1;
    }

    /**
     * Test if beyond the last record in the result set.
     *
     * @return bool
     */
    public function EOF()
    {
        $tmp = ($this->_pos >= $this->RecordCount());
        return $tmp;
    }

    /**
     * @ignore
     */
    public function Close()
    {
        // do nothing
    }

    /**
     * Get the current row from the result set.
     *
     * @return mixed
     */
    public function &get_object()
    {
        $keys = array_keys($this->_filter);
        $key = $keys[$this->_pos];
        return $this->_filter[$key];
    }

    /**
     * @ignore
     */
    public function &get_pagination()
    {
        die('not implemented: '.__METHOD__);
    }

    /**
     * Fetch all of the results.
     *
     * @return array
     */
    public function FetchAll()
    {
        return $this->_filter;
    }

    public function __get($key)
    {
        switch( $key ) {
        case 'EOF':
            return $this->EOF();

        case 'fields':
            $keys = array_keys($this->_filter);
            $key = $keys[$this->_pos];
            return $this->_filter[$key];
        }
    }
} // end of class