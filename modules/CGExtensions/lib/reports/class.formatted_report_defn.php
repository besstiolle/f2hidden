<?php

/**
 * This file defines the abstract report definition class.
 *
 * @package CGExtensions
 * @category Reports
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2014 by Robert Campbell
 */

namespace CGExtensions\reports;

/**
 * A simple class that generates a report using a template to format the layout of each item or row.
 * This class is used by the formatted_report_generator to generate output.
 *
 * @see formatted_report_generator
 */
class formatted_report_defn extends report_defn
{
    /**
     * @ignore
     */
    private $_item_template;

    /**
     * Set the item template.
     *
     * @param string $tpl The name of the item template. If the parameter ends with .tpl a file template will be assumed.
     */
    public function set_item_template($tpl)
    {
        $tpl = trim($tpl);
        if( $tpl ) $this->_item_template = $tpl;
    }

    /**
     * Get the name of the item template.
     *
     * @return string
     */
    public function get_item_template()
    {
        return $this->_item_template;
    }

} // end of class

?>