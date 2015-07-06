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
abstract class resultset extends base_resultset
{
    /**
     * A member to store the database recordset.
     */
    protected $_rs;

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
    //abstract protected function _query();

    /**
     * Get the number of records returned in this recordset.
     *
     * @return int
     */
    public function RecordCount()
    {
        $this->_query();
        if( $this->_rs ) return $this->_rs->RecordCount();
    }

    /**
     * Move the pointer to the next matching row in the recordset.
     */
    public function MoveNext()
    {
        $this->_query();
        if( $this->_rs ) return $this->_rs->MoveNext();
    }

    /**
     * Move the pointer  to the first matching row in the recordset.
     */
    public function MoveFirst()
    {
        $this->_query();
        if( $this->_rs ) return $this->_rs->MoveFirst();
    }

    /**
     * Move the pointer to the first matching row in the recordset.
     */
    public function Rewind()
    {
        return $this->MoveFirst();
    }

    /**
     * Move the pointer to the last matching row in the recordset.
     */
    public function MoveLast()
    {
        $this->_query();
        if( $this->_rs ) return $this->_rs->MoveLast();
    }

    /**
     * Test if the pointer is at the end of the recordset (there are no more records)
     *
     * @return bool
     */
    public function EOF()
    {
        $this->_query();
        if( $this->_rs ) return $this->_rs->EOF();
        return TRUE;
    }

    /**
     * Close the recordset, and free resources.
     */
    public function Close()
    {
        if( $this->_rs ) return $this->_rs->Close();
    }

    /**
     * @ignore
     */
    public function __get($key)
    {
        if( $key == 'EOF' ) return $this->EOF();
        if( $key == 'fields' && $this->_rs && !$this->_rs->EOF() ) return $this->_rs->fields;
        throw new \CmsInvalidDataException("$key is not a gettable member of ".__CLASS__);
    }

    /**
     * return the total number of matches (independent of limit and offset)
     *
     * @return int
     */
    public function TotalMatches()
    {
        $this->_query();
        return $this->_totalmatching;
    }

    /**
     * Get an object representing the data at the current pointer position
     *
     * @return object
     */
    //abstract public function &get_object();

    /**
     * Get a pagination object for this query and resultset
     *
     * @return pagination
     */
    public function &get_pagination()
    {
        $pagination = new pagination($this);
        return $pagination;
    }

    /**
     * Fetch all of the records in this resultset as an array of objects.
     *
     * @return object[]
     */
    public function FetchAll()
    {
        $out = array();
        $this->MoveFirst();
        while( !$this->EOF() ) {
            $out[] = $this->get_object();
            $this->MoveNext();
        }
        return $out;
    }

    /**
     * A convenience method used to aide in converting a string that may (or may not) contain wildcard (*) characters
     * into a string suitable for use in a substring match
     *
     * @param string $str The string to parse for wildcards.
     */
    protected function wildcard($str)
    {
        if( strpos($str,'*') != FALSE ) {
            $str = str_replace('*','%',$str);
        }
        else if( strpos($str,'%') === FALSE ) {
            $str = '%'.$str.'%';
        }
        return $str;
    }
}

?>