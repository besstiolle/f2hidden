<?php

namespace CGExtensions\reports;

// a class defining the contents and formatting of a report
abstract class report_defn
{
    private $_query;           // object of type query
    private $_title;           // string
    private $_desc;            // string

    public function set_query(\CGExtensions\query\query $query)
    {
        $this->_query = $query;
    }

    protected function &get_query()
    {
        return $this->_query;
    }

    public function get_resultset()
    {
        return $this->_query->execute();
    }

    public function get_title()
    {
        return $this->_title;
    }

    public function set_title($str)
    {
        $this->_title = trim($str);
    }

    public function get_description()
    {
        return $this->_desc;
    }

    public function set_description($str)
    {
        $this->_desc = trim($str);
    }

} // end of class

?>