<?php

/**
 * This file contains classes for defining a javascript library.
 *
 * @package CGExtensions
 * @category jsloader
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2014 by Robert Campbell
 */

namespace CGExtensions\jsloader;

/**
 * A class to define a javascript library.
 *
 * @property string $name The library name
 * @property callable $callback An optional callback of the form  function($name) to return javascript code.  Only one of the jsfile, jsurl, callback, or module properties must be specified.
 * @property string[] $depends An array of library names that this library depends upon.
 * @property string $jsfile The complete pathname to the javascript file for this library.  Only one of the jsfile, jsurl, callback, or module properties must be specified.
 * @property string $cssfile The complete pathname to a css file to associate with this libarary
 * @property string $jsurl A URL to a remote javascript library.  Only one of the jsfile, jsurl, callback, or module properties can be specified.
 * @property string $cssurl A complete URL to a remote CSS library.
 * @property string $module The name of a module to query to get javascript code.
 */
class libdefn
{
    /**
     * @ignore
     */
    private $_data = array();

    /**
     * Constructor
     *
     * @param string $name The name of the javascript library we are defiing (should not contain spaces or other characters that require encoding)
     */
    public function __construct($name)
    {
        $this->name = trim($name);
    }

    /**
     * @ignore
     */
    public function __get($key)
    {
        $key = strtolower($key);
        switch( $key ) {
        case 'name':
        case 'callback':
        case 'depends':
        case 'jsfile':
        case 'cssfile':
        case 'jsurl':
        case 'cssurl':
        case 'module':
            if( isset($this->_data[$key])) return $this->_data[$key];
            break;

        default:
            stack_trace(); die();
            throw new \CmsInvalidDataException($key.' is not a valid member of a '.__CLASS__.' object');
        }
    }

    /**
     * @ignore
     */
    public function __isset($key)
    {
        $key = strtolower($key);
        switch( $key ) {
        case 'name':
        case 'callback':
        case 'depends':
        case 'jsfile':
        case 'cssfile':
        case 'jsurl':
        case 'cssurl':
        case 'code':
        case 'cssname':
        case 'styles':
        case 'lib':
        case 'module':
            return isset($this->_data[$key]);

        default:
            throw new \CmsInvalidDataException($key.' is not a valid member of a '.__CLASS__.' object');
        }
    }

    /**
     * @ignore
     */
    public function __set($key,$val)
    {
        $key = strtolower($key);
        switch( $key ) {
        case 'name':
            $val = strtolower(trim($val));
            if( !$val ) throw new \CmsInvalidDataException('name cannot be empty in a '.__CLASS__.' object');
            $this->_data[$key] = $val;
            break;

        case 'callback':
            if( is_string($val)) $val = trim($val);
            if( $val && !is_callable($val) ) throw new \CmsInvalidDataException('callback not callable for a '.__CLASS__.' object');
            $this->_data[$key] = $val;
            break;

        case 'cssfile':
            if( !$val ) throw new \CmsInvalidDataException('css value must be valid, if specified for a '.__CLASS__.' object');
            if( !is_array($val) ) $val = array($val);
            foreach( $val as $one ) {
                if( !file_exists($one) ) throw new \CmsInvalidDataException('All CSS files must exist if specified for a '.__CLASS__.' object');
            }
            $this->_data[$key] = $val;
            break;

        case 'cssurl':
            if( !$val ) throw new \CmsInvalidDataException('css value must be valid, if specified for a '.__CLASS__.' object');
            if( !is_array($val) ) $val = array($val);
            foreach( $val as $one ) {
                if( !startswith($one,'http') && !startswith($one,'//') ) {
                    throw new \CmsInvalidDataException('All CSS files must exist if specified for a '.__CLASS__.' object');
                }
            }
            $this->_data[$key] = $val;
            break;

        case 'jsfile':
            if( !$val ) throw new \CmsInvalidDataException('js value must be valid, if specified for a '.__CLASS__.' object');
            if( !is_array($val) ) $val = array($val);
            foreach( $val as $one ) {
                if( !file_exists($one) ) {
                    die("not found: $one");
                    throw new \CmsInvalidDataException('All JS files must exist if specified for a '.__CLASS__.' object');
                }
            }
            $this->_data[$key] = $val;
            break;

        case 'jsurl':
            if( !$val ) throw new \CmsInvalidDataException('js value must be valid, if specified for a '.__CLASS__.' object');
            if( !is_array($val) ) $val = array($val);
            foreach( $val as $one ) {
                if( !startswith($one,'http') && !startswith($one,'//') ) {
                    throw new \CmsInvalidDataException('All CSS files must exist if specified for a '.__CLASS__.' object');
                }
            }
            $this->_data[$key] = $val;
            break;


        case 'depends':
            if( $val ) {
                if( !is_array($val) ) $val = explode(',',$val);
                $tmp = array();
                foreach( $val as $one ) {
                    $tmp[] = trim($one);
                }
                $this->_data[$key] = $tmp;
            }
            else {
                $this->_data[$key] = $val;
            }
            break;

        case 'module':
            $this->_data[$key] = trim($val);
            break;

        default:
            throw new \CmsInvalidDataException($key.' is not a valid member of a '.__CLASS__.' object');
        }
    }

    /**
     * Test if this object is valid.
     *
     * @return bool
     */
    public function valid()
    {
        if( $this->name == '' ) return FALSE;
        if( !$this->jsfile && !$this->callback && !$this->module ) return FALSE;
        return TRUE;
    }
} // end of class

?>
