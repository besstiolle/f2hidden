<?php

namespace CGExtensions\query;

class txtfilequery extends query
{
    private $_data = array('limit'=>500,'offset'=>0,'filename'=>null);

    public function __construct($parms = array())
    {
        foreach( $parms as $key => $val ) {
            $this->OffsetSet($key,$val);
        }
    }

    public function OffsetGet($key)
    {
        if( array_key_exists($key,$this->_data) ) return $this->_data[$key];
    }

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

    public function OffsetExists($key)
    {
        if( array_key_exists($key,$this->_data) ) return TRUE;
        return FALSE;
    }

    public function &execute()
    {
        $obj = new txtfileresultset($this);
        return $obj;
    }
}