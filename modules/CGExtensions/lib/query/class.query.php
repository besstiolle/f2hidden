<?php

namespace CGExtensions\query;

/**
 * An abstract class to provide the basic interface for a query class.
 * descendents of this class should implement at least the limit, and offset parameters
 * to allow the pagination class to work.
 */
abstract class query implements \ArrayAccess
{
    /**
     * Constructor.
     * This method accepts an array of parameters, and sets internal data for the query object.
     * Identically to calling $this['key'] = $val multiple times.
     *
     * @param array $parms
     */
    abstract public function __construct($parms = array());

    /**
     * Return the value of a currently set variable.
     *
     * @param string $key
     * @return mixed
     */
    abstract public function OffsetGet($key);

    /**
     * Set a value into the query object.
     *
     * @param string $key
     * @param mixed $value
     */
    abstract public function OffsetSet($key,$value);

    /**
     * Test if the key is set in the data object.
     *
     * @param string $key
     * @return bool
     */
    abstract public function OffsetExists($key);

    /**
     * Unset a variable in the object
     *
     * @param string $key
     */
    public function OffsetUnset($key)
    {
        // do nothing
    }

    /**
     * Execute the query and return the resultset
     *
     * @return resultset
     */
    abstract public function &execute();
}

?>