<?php

namespace CGExtensions\query;

class array_query extends query
{
    /**
     * @ignore
     */
    private $_data;

    /**
     * Construct an array_query from an array of data.
     *
     * @param array $data.
     */
    public function __construct($data = array())
    {
        // in this instance, the data passed to the constructor IS the query data
        if( !is_array($data) ) throw new \RuntimeException('Invalid data passed to '.__METHOD__);

        $this->_data = $data;
    }

    /**
     * @ignore
     */
    public function OffsetGet($key)
    {
        return; // do nothing
    }

    /**
     * @ignore
     */
    public function OffsetSet($key,$val)
    {
        return; // do nothing
    }

    /**
     * @ignore
     */
    public function OffsetExists($key)
    {
        return TRUE;
    }

    /**
     * @ignore
     */
    public function &execute()
    {
        $obj = new array_resultset($this);
        return $obj;
    }

    /**
     * @ignore
     */
    public function data()
    {
        return $this->_data;
    }
} // end of class

?>