<?php

/**
 * This file contains the sql_resultset class.
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2014 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * A class to allow iterating and fetching the results from an SQL query.
 */
class sql_resultset extends resultset
{
    /**
     * Constructor
     *
     * @param sql_query $query The input query object
     */
    public function __construct(sql_query $query)
    {
        $this->_filter = $query;
    }

    /**
     * @ignore
     * @internal
     */
    protected function _query()
    {
        if( $this->_rs ) return;

        $sql = $this->_filter['sql'];
        // get the first two words out of the query
        list($w1,$w2,$junk) = explode(' ',$sql);
        if( strtoupper($w1) == 'SELECT' ) {
            if( strtoupper($w2) != 'SQL_CALC_FOUND_ROWS') {
                // inject SQL_CALC_FOUND_ROWS
                $sql = substr_replace($sql,'SELECT SQl_CALC_FOUND_ROWS',0,strlen('SELECT'));
            }
        }

        $db = \cge_utils::get_db();
        $this->_rs = $db->SelectLimit($sql,$this->_filter['limit'],$this->_filter['offset'],
                                      $this->_filter['parms']);
        $this->_totalmatching = (int) $db->GetOne('SELECT FOUND_ROWS()');
    }

    /**
     * Get the object associated with the resultset.
     * This method is not utilized for this class, and will throw an exception.
     */
    public function &get_object()
    {
        $row = $this->fields;
        return $row;
    }
} // end of class

?>