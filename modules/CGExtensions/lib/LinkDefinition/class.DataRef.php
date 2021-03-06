<?php

namespace CGExtensions\LinkDefinition;

/**
 * A class to abstract a data reference
 * i.e: a content page, stylesheet, or a module data item
 * this can then be used by the DataReferenceLinkGenerator stuff
 * to generate links to these data items
 */
class DataRef
{
    private $_data = array('key1'=>null,'key2'=>null,'key3'=>null,'key4'=>null);

    /**
     * Constructor
     *
     * @param array $parms An associative array of parameters
     */
    public function __construct($parms = array())
    {
        foreach( $parms as $key => $val ) {
            $this->$key = $val;
        }
    }

    /**
     * @ignore
     */
    public function __get($key)
    {
        if( array_key_exists($key,$this->_data) ) return $this->_data[$key];
    }

    /**
     * @ignore
     */
    public function __set($key,$val)
    {
        if( !array_key_exists($key,$this->_data) ) throw new \Exception($key.' is not an invalid member of '.__CLASS__);
        $this->_data[$key] = $val;
    }

    /**
     * @ignore
     */
    public function __isset($key)
    {
        return isset($this->_data[$key]);
    }

    /**
     * @ignore
     */
    public function __unset($key)
    {
        if( array_key_exists($key,$this->_data) ) $this->_data[$key] = null;
    }

    /**
     * @ignore
     */
    public function __toString()
    {
        return '__DataRef::'.implode('::',array_values($this->_data));
    }

    /**
     * Create a new dataref object from a string
     * @param string Input string of the format __DataRef::key1val::key2val::key3val::key4val
     * @return DataRef
     */
    public static function fromString($str)
    {
        if( !startswith($str,'__DataRef::') ) throw new \Exception('String provided is not a valid dataref');
        $parts = explode('::',$str);
        if( count($parts) < 2 || count($parts) > 5 )  throw new \Exception('String provided is not a valid dataref');

        $obj = new self();
        for( $i = 1; $i < count($parts); $i++ ) {
            $key = 'key'.$i;
            $obj->$key = $parts[$i];
        }
        return $obj;
    }
} // end of class

#
# EOF
#
?>