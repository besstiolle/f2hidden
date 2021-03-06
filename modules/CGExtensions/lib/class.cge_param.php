<?php
#BEGIN_LICENSE
#-------------------------------------------------------------------------
# Module: CGExtensions (c) 2008-2014 by Robert Campbell
#         (calguy1000@cmsmadesimple.org)
#  An addon module for CMS Made Simple to provide useful functions
#  and commonly used gui capabilities to other modules.
#
#-------------------------------------------------------------------------
# CMSMS - CMS Made Simple is (c) 2005 by Ted Kulp (wishy@cmsmadesimple.org)
# Visit the CMSMS Homepage at: http://www.cmsmadesimple.org
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------
#END_LICENSE

/**
 * A set of utilities for cleaning input parameters.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2015 by Robert Campbell
 */

/**
 * A set of utilities for cleaning input parameters.
 *
 * @package CGExtensions
 */
final class cge_param
{
    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * Get safe HTML from an input parameter.
     * This method uses htmlawed to clean input HTML.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_html($params,$key,$dflt = null)
    {
        $val = \cge_utils::get_param($params,$key,$dflt);
        $val = html_entity_decode($val);
        return \cge_utils::clean_input_html($val);
    }

    /**
     * Get a safe integer from an input parameter.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param int $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_int($params,$key,$dflt = null)
    {
        $dflt = (int) $dflt;
        return (int) cge_utils::get_param($params,$key,$dflt);
    }

    /**
     * Get a safe boolean from an input parameter.
     * This method can accept boolean strings like yes, no, true, false, on, off.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param bool $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_bool($params,$key,$dflt = null)
    {
        $dflt = (bool) $dflt;
        $val = cge_utils::get_param($params,$key,$dflt);
        return cge_utils::to_bool($val);
    }

    /**
     * Get a safe string from an input parameter.
     * The string is stripped of any javascript or html code.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_string($params,$key,$dflt = null)
    {
        $val = html_entity_decode(cge_utils::get_param($params,$key,$dflt));
        return trim(strip_tags($val));
    }

    /**
     * Get a safe float from an input parameter.
     *
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param float $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_float($params,$key,$dflt = null)
    {
        return (float) cge_utils::get_param($params,$key,$dflt);
    }

    /**
     * Get a safe array of strings from an input parameter that is an array.
     *
     * @see cge_param::get_string()
     * @param array $params An associative array of input params
     * @param string $key The key to the associative array
     * @param string[] $dflt The default value to use if the key does not exist in the $params aray.
     */
    public static function get_string_array($params,$key,$dflt = null)
    {
        $tmp = \cge_utils::get_param($params,$key,$dflt);
        if( !is_array($tmp) ) $tmp = array($tmp);

        for( $i = 0; $i < count($tmp); $i++ ) {
            $tmp[$i] = html_entity_decode($tmp[$i]);
            $tmp[$i] = trim(strip_tags($tmp[$i]));
        }
        return $tmp;
    }
} // end of class

#
# EOF
#
?>