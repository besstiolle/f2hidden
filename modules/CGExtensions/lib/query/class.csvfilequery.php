<?php

/**
 * This class defines the csvfilequery class
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * A class that represents the contents of a csv file as a query.
 * This class does not provide for filtering of the contents of the csv file
 *
 * @property string $delimiter The field delimiter for the csv file.  The default value is a commma (,)
 * @property string $enclosure The enclosure that field contents may be enclosed in (particularly if the delimiter may be present in the field contents).  The default value is the double quote (")
 * @property array $map An array that can be used to rename all of the columns of the input file from the standard col_<index> format to real names.
 */
class csvfilequery extends txtfilequery
{
    /**
     * @ignore
     */
    private $_data = array('delimiter'=>',','enclosure'=>'"','map'=>null);

    /**
     * Constructor
     *
     * @param array $params The default properties for this query.
     */
    public function __construct($params = array())
    {
        foreach( $params as $key => $val ) {
            switch( $key ) {
            case 'delimiter':
            case 'enclosure':
            case 'map':
                $this->_data[$key] = $val;
                unset($params[$key]);
                break;
            }
        }
        parent::__construct($params);
    }

    /**
     * @ignore
     */
    public function OffsetGet($key)
    {
        if( array_key_exists($key,$this->_data) ) return $this->_data[$key];
        return parent::OffsetGet($key);
    }

    /**
     * @ignore
     */
    public function OffsetSet($key,$value)
    {
        switch( $key ) {
        case 'delimiter':
        case 'enclosure':
            $this->_data[$key] = $value;
            break;

        default:
            parent::OffsetSet($key,$value);
        }
    }

    /**
     * @ignore
     */
    public function OffsetExists($key)
    {
        if( array_key_exists($key,$this->_data) ) return TRUE;
        return parent::OffsetExists($key);
    }

    /**
     * Execute the query and generate a resultset.
     *
     * @return csvfileresultset
     */
    public function &execute()
    {
        $obj = new csvfileresultset($this);
        return $obj;
    }
}