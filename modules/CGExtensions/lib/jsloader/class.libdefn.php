<?php

namespace CGExtensions\jsloader;

class libdefn
{
    private $_data = array();

    public function __construct($name)
    {
        $this->name = $name;
    }

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
            $val = trim($val);
            $obj = \cms_utils::get_module($val);
            if( !is_object($obj) ) throw new \CmsInvalidDataException('module '.$val.' cannot be loaded');
            $this->_data[$key] = $val;
            break;

        default:
            throw new \CmsInvalidDataException($key.' is not a valid member of a '.__CLASS__.' object');
        }
    }

    public function valid()
    {
        if( $this->name == '' ) return FALSE;
        if( !$this->jsfile && !$this->callback && !$this->module ) return FALSE;
        return TRUE;
    }
} // end of class

?>
