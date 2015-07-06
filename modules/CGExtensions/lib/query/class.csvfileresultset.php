<?php

/**
 * This file defines the csvfileresultset class
 *
 * @package CGExtensions
 * @category Query
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\query;

/**
 * A class to return records out of a csv file, suitable for using in some reports.
 *
 * @property string $fields Get the current record (in this case a line).
 * @see csvfilequery
 */
class csvfileresultset extends txtfileresultset
{
    /**
     * @ignore
     */
    private $_loaded;

    /**
     * @ignore
     */
    protected function _query()
    {
        parent::_query();
        if( $this->_loaded ) return;
        $this->_loaded = 1;

        $obj = $this->get_fileobject();
        $obj->SetFlags(\SplFileObject::READ_CSV);
        $obj->setCsvControl($this->_filter['delimiter'],$this->_filter['enclosure']);
        $obj->seek($this->_filter['offset']); // just in case
    }

    /**
     * @ignore
     */
    public function __get($key)
    {
        if( $key == 'fields' ) {
            $rec = array();
            $rec['line'] = $this->get_fileobject()->key() + 1;
            $cur = $this->get_fileobject()->current();
            $map = $this->_filter['map'];
            if( is_array($map) ) {
                foreach( $map as $col => $fldname ) {
                    $rec[$fldname] = null;
                    if( isset($cur[$col]) ) $rec[$fldname] = $cur[$col];
                }
            }
            else {
                foreach( $cur as $key => $val ) {
                    $rec['col_'.$key] = $val;
                }
            }
            return $rec;
        }
        return parent::__get($key);
    }

} // end of class
?>