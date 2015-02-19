<?php

namespace CGExtensions\LinkDefinition;

/**
 * A class with all of the information necessary to create a link definition <a> tag.
 */
class LinkDefinition
{
    private $_data = array();

    public function __construct($parms = array())
    {
        foreach( $parms as $key => $val ) {
            $this->$key = $val;
        }
    }

    private function _is_valid_key($key)
    {
        switch( $key ) {
        case 'download':
        case 'href':
        case 'hreflang':
        case 'media':
        case 'id':
        case 'name': // deprecated
        case 'rel':
        case 'target':
        case 'type':
        case 'class':
        case 'accesskey':
        case 'contenteditable':
        case 'contextmenu':
        case 'dir':
        case 'draggable':
        case 'dropzone':
        case 'hidden':
        case 'lang':
        case 'spellcheck':
        case 'style':
        case 'tabindex':
        case 'title':
        case 'translate':
        case 'text': // magic... it's the text of the link.
            return TRUE;

        default:
            if( startswith($key,'data') ) {
                return TRUE;
            }
            else if( startswith($key,'on') ) {
                return TRUE;
            }
            return FALSE;
        }
    }

    public function __get($key)
    {
        if( !$this->_is_valid_key($key) ) throw new \Exception($key.' is not a valid member for '.__CLASS__);
        if( array_key_exists($key,$this->_data) ) return $this->_data[$key];
    }

    public function __set($key,$val)
    {
        // anything set here must be representable as a string
        $val = (string) $val;
        if( !$this->_is_valid_key($key) ) throw new \Exception($key.' is not a valid member for '.__CLASS__);
        $val = (string) $val;
        $this->_data[$key] = $val;
    }

    public function __isset($key)
    {
        if( !$this->_is_valid_key($key) ) throw new \Exception($key.' is not a valid member for '.__CLASS__);
        return array_key_exists($key,$this->_data) && $this->_data[$key] != null;
    }

    public function __unset($key)
    {
        if( !$this->_is_valid_key($key) ) throw new \Exception($key.' is not a valid member for '.__CLASS__);
        unset($this->_data[$key]);
    }

    public function __toString()
    {
        return $this->draw();
    }

    public function validate()
    {
        // only required portion is the href
        if( !isset($this->href) ) throw new \RuntimeException('This link definition is invalid (no href attribute)');
        if( !isset($this->text) ) throw new \RuntimeException('This link definition is invalid (no text attribute)');
    }

    public function draw()
    {
        $this->validate();

        $tmp = array();
        foreach( $this->_data as $key => $val ) {
            if( $key == 'text' ) continue;
            $tmp[] = $key.'="'.$val.'"';
        }
        $out = '<a '.implode(' ',$tmp).'>'.htmlentities($this->text).'</a>';
        return $out;
        // die: todo.
    }
}

?>