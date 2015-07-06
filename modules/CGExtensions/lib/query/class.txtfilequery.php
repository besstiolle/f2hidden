<?php

/**
 * This file defines the txtfilequery class
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * A class to generate a query object from a text file.
 *
 * @property int $limit The limit of records to use
 * @property int $offset The start record (line) to use in the report.
 * @property string $filename The absolute path to the file to use in the report.
 */
class txtfilequery extends query
{
    /**
     * @ignore
     */
    private $_data = array('limit'=>500,'offset'=>0,'filename'=>null);

    /**
     * Constructor
     *
     * @param array $parms The default properties for this object.
     */
    public function __construct($parms = array())
    {
        foreach( $parms as $key => $val ) {
            $this->OffsetSet($key,$val);
        }
    }

    /**
     * @ignore
     */
    public function OffsetGet($key)
    {
        if( array_key_exists($key,$this->_data) ) return $this->_data[$key];
    }

    /**
     * @ignore
     */
    public function OffsetSet($key,$val)
    {
        switch( $key ) {
        case 'limit':
            $val = (int)$val;
            $val = max(1,$val);
            $val = min(1000,$val);
            $this->_data[$key] = $val;
            break;

        case 'offset':
            $val = (int)$val;
            $val = max(0,$val);
            $this->_data[$key] = $val;
            break;

        case 'filename':
            $val = trim($val);
            if( !is_readable($val) ) throw new \CmsInvalidDataException('File '.$val.' does not exist for '.__CLASS__);
            $this->_data[$key] = $val;
            break;

        default:
            throw new \CmsInvalidDataException($key.' is not a valid property for a '.__CLASS__.' object');
        }
    }

    /**
     * @ignore
     */
    public function OffsetExists($key)
    {
        if( array_key_exists($key,$this->_data) ) return TRUE;
        return FALSE;
    }

    /**
     * Execute the query and return a resultset.
     *
     * @return txtfileresultset
     */
    public function &execute()
    {
        $obj = new txtfileresultset($this);
        return $obj;
    }
}