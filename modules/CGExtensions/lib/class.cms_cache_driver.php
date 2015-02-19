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
 * This file contains an abstract base class for all cache drivers.
 *
 * @package CGExtensions
 * @category Utility
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

/**
 * An abstract base class for all cache drivers.
 * Cache drivers should work with cache groups and should have a preset group.
 * most implementations of a cache driver will accept the name of a default cache group in its constructor.
 *
 * @package CGExtensions
 */
abstract class cms_cache_driver
{
    /**
     * Clear a cache group
     * (or the default cache group).
     *
     * @param string $group The cache group
     */
    abstract public function clear($group = '');

    /**
     * Retrieve a cached value.
     *
     * @param string $key The variable key.
     * @param string $group The cache group
     */
    abstract public function get($key,$group = '');

    /**
     * Test if a value exists in the cache
     *
     * @param string $key The variable key.
     * @param string $group The cache group
     */
    abstract public function exists($key,$group = '');

    /**
     * Erase a cached value.
     *
     * @param string $key The variable key.
     * @param string $group The cache group
     */
    abstract public function erase($key,$group = '');

    /**
     * Store a value into the cache.
     *
     * @param string $key The variable key.
     * @param string $value The data to store.
     * @param string $group The cache group
     */
    abstract public function set($key,$value,$group = '');
} // end of class

?>