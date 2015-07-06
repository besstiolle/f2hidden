<?php

/**
 * This class defines the pagination class.
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * A class to assist in building a paginator navigation for the results of a query.
 *
 * @property-read int $pagecount The number of pages
 * @property-read int $page The current page number (one based)
 * @property-read int pagelimit The page limit from the query object
 * @property-read int totalroas The total matching rows for the resultset (independent of limit or offset)
 */
class pagination implements \ArrayAccess
{
    /**
     * @ignore
     */
    private $_rs;

    /**
     * The constructor
     *
     * @see resultset
     * @see query
     * @param resultset $rs A resultset object )
     */
    public function __construct(resultset $rs)
    {
        $this->_rs = $rs;
    }

    /**
     * @ignore
     */
    public function OffsetGet($key)
    {
        switch( $key ) {
        case 'pagecount':
            $n = $this->_rs->TotalMatches();
            $p = (int) ceil($n / $this->_rs->get_query()['limit']);
            return $p;

        case 'page':
            $p = (int)($this->_rs->get_query()['offset'] / $this->_rs->get_query()['limit']) + 1;
            return $p;

        case 'pagelimit':
            return $this->_rs->get_query()['limit'];

        case 'totalrows':
            return $this->_rs->TotalMatches();

        default:
            throw new \RuntimeException($key.' is not a member of '.__CLASS__);
        }
    }

    /**
     * @ignore
     */
    public function OffsetSet($key,$value)
    {
        // do nothing
    }

    /**
     * @ignore
     */
    public function OffsetExists($key)
    {
        // do nothing
    }

    /**
     * @ignore
     */
    public function OffsetUnset($key)
    {
        // do nothing
    }

    /**
     * Get a list of page numbers suitable for using in a loop to build a navigation list.
     * This method will use optimization to ensure that the number of items returned in the list will never grow too large
     *
     * @param int $surround - The number of page numbers to surround the current page with.
     * @return int[]
     */
    public function get_pagelist($surround = 3)
    {
        $list = array();
        for( $i = 1; $i <= min($surround,$this['pagecount']); $i++ ) {
            $list[] = (int)$i;
        }

        $x1 = max(1,(int)($this['page'] + $surround / 2));
        $x2 = min($this['pagecount'],(int)($this['page'] + $surround / 2) );
        for( $i = $x1; $i <= $x2; $i++ ) {
            $list[] = (int)$i;
        }

        for( $i = max(1,$this['pagecount'] - $surround); $i <= $this['pagecount']; $i++) {
            $list[] = (int)$i;
        }

        $list = array_unique($list);
        sort($list);
        return $list;
    }

    /**
     * Get a hash of page numbers suitable for using in a loop to build a navigation list.
     *
     * @see get_pagelit
     * @param int $surround The number of pages around the current page (and the beginning and end) to return.
     * @return array
     */
    public function get_pagehash($surround = 3)
    {
        $list = $this->get_pagelist($surround);
        $out = array();
        foreach( $list as $one ) {
            $out[$one] = $one;
        }
        return $out;
    }
} // end of class

?>
