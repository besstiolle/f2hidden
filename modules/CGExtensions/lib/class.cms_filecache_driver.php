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
 * A cache driver that stores cached data in the CMSMS tmp/cache directory.
 * This class supports file locking, and serializing advanced data.  It supports
 * storing files in munged filenames (so that the content of files is difficult to discern by
 * the filename,  locking blocking, and grouping cached data.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

/**
 * A cache driver that stores cached data in the CMSMS tmp/cache directory.
 * This class supports file locking, and serializing advanced data.  It supports
 * storing files in munged filenames (so that the content of files is difficult to discern by
 * the filename,  locking blocking, and grouping cached data.
 *
 * parameters:
 *   lifetime int (cache lifetime in seconds) (default of 300 seconds)
 *   locking  bool (enable locking) (off by default)
 *   cache_dir string (Cache directory.  default is /tmp
 *   auto_cleanup bool (automatically clean expired caches) default is 0
 *   blocking bool auto enable blocking (default is off)
 *   group string Default cache group name.
 *
 * <code>
 * $driver = new cms_filecache_driver(array('cache_dir'=>TMP_CACHE_LOCATION,'group'=>'webpages'));
 * </code>
 *
 * @package CGExtensions
 */
class cms_filecache_driver extends cms_cache_driver
{
    const LOCK_READ   = '_read';
    const LOCK_WRITE  = '_write';
    const LOCK_UNLOCK = '_unlock';
    const KEY_SERIALIZED = '__SERIALIZED__';

    /**
     * @ignore
     */
    private $_key;

    /**
     * @ignore
     */
    private $_lifetime = 300;

    /**
     * @ignore
     */
    private $_locking = false;

    /**
     * @ignore
     */
    private $_blocking = false;

    /**
     * @ignore
     */
    private $_cache_dir = '/tmp';

    /**
     * @ignore
     */
    private $_group = 'default';

    /**
     * @ignore
     */
    private $_auto_cleaning = 0;

    /**
     * The constructor
     * @param array $opts Optional parameters.
     */
    public function __construct($opts)
    {
        $_keys = array('lifetime','locking','cache_dir','auto_cleaning','blocking','group');
        if( is_array($opts) ) {
            foreach( $opts as $key => $value ) {
                if( in_array($key,$_keys) ) {
                    $tmp = '_'.$key;
                    $this->$tmp = $value;
                }
            }
        }
    }

    /**
     * Get data from the cache
     *
     * <code>
     * $driver = new cms_filecache_driver(array('cache_dir'=>TMP_CACHE_LOCATION,'group'=>'webpages'));
     * echo $driver->get('http://www.cmsmadesimple.org');
     * </code>
     *
     * @param string $key the data key.
     * @param string $group An optional group name (of not specified the default group stored in the object is used.
     */
    public function get($key,$group = '')
    {
        if( !$group ) $group = $this->_group;

        $this->_key = $key;
        $this->_auto_clean_files();
        $fn = $this->_get_filename($key,$group);
        $data = $this->_read_cache_file($fn);
        return $data;
    }


    /**
     * Clear cached values.
     *
     * @param string $group An optional group name (of not specified the default group stored in the object is used.
     */
    public function clear($group = '')
    {
        return $this->_clean_dir($this->_cache_dir,$group,false);
    }


    /**
     * Test if the specified data exists in the cache
     *
     * @param string $key the data key.
     * @param string $group An optional group name (of not specified the default group stored in the object is used.
     */
    public function exists($key,$group = '')
    {
        if( !$group ) $group = $this->_group;

        $this->_auto_clean_files();
        $fn = $this->_get_filename($key,$group);
        clearstatcache();
        if( @file_exists($fn) ) return TRUE;
        return FALSE;
    }


    /**
     * Erase the specified data from the cache.
     *
     * @param string $key the data key.
     * @param string $group An optional group name (of not specified the default group stored in the object is used.
     */
    public function erase($key,$group = '')
    {
        if( !$group ) $group = $this->_group;

        $fn = $this->_get_filename($key,$group);
        if( @file_exists($fn) ) {
            @unlink($fn);
            return TRUE;
        }
        return FALSE;
    }


    /**
     * Store data into the cache
     *
     * <code>
     * $driver = new cms_filecache_driver(array('cache_dir'=>TMP_CACHE_LOCATION,'group'=>'webpages'));
     * $data = file_get_contents('http://www.cmsmadesimple.org');
     * $driver->set('http://www.cmsmadesimple.org',$data);
     * </code>
     *
     * @param string $key the data key.
     * @param string $value The data to store.
     * @param string $group An optional group name (of not specified the default group stored in the object is used.
     */
    public function set($key,$value,$group = '')
    {
        if( !$group ) $group = $this->_group;

        $fn = $this->_get_filename($key,$group);
        return $this->_write_cache_file($fn,$value);
    }


    /**
     * Touch data in the cache to prevent expiry
     *
     * @param string $key the data key.
     * @param string $group An optional group name (of not specified the default group stored in the object is used.
     */
    public function touch($key,$group = '')
    {
        if( !$group ) $group = $this->_group;

        $fn = $this->_get_filename($key,$group);
        if( file_exists($fn) ) @touch($fn);
    }


    /**
     * @ignore
     */
    private function _get_filename($key,$group)
    {
        $fn = $this->_cache_dir . '/dcache_'.md5($group).'_'.md5($key).'.cg';
        return $fn;
    }


    /**
     * @ignore
     */
    private function _flock($res,$flag)
    {
        if( !$this->_locking ) return TRUE;
        if( !is_resource($res) ) return TRUE; // weird.

        $mode = '';
        switch( strtolower($flag) ) {
        case self::LOCK_READ:
            $mode = LOCK_SH;
            break;

        case self::LOCK_WRITE:
            $mode = LOCK_EX;
            break;

        case self::LOCK_UNLOCK:
            $mode = LOCK_UN;
        }

        if( $this->_blocking ) return flock($res,$mode);

        // non blocking lock
        $mode = $mode | LOCK_NB;
        for( $n = 0; $n < 5; $n++ ) {
            $tmp = flock($res,$mode);
            if( $tmp ) return TRUE;
            $tl = rand(1,300);
            usleep($tl);
        }
        return FALSE;
    }

    /**
     * @ignore
     */
    private function _read_cache_file($fn)
    {
        $this->_cleanup($fn);
        $data = null;
        if( @file_exists($fn) ) {
            clearstatcache();
            $fp = @fopen($fn,'rb');
            if( $fp ) {
                if( $this->_flock($fp,self::LOCK_READ) ) {
                    $len = @filesize($fn);
                    if( $len > 0 ) $data = fread($fp,$len);
                    $this->_flock($fp,self::LOCK_UNLOCK);
                }
                @fclose($fp);

                if( startswith($data,self::KEY_SERIALIZED) ) $data = unserialize(substr($data,strlen(self::KEY_SERIALIZED)));
                return $data;
            }
        }
    }


    /**
     * @ignore
     */
    private function _cleanup($fn)
    {
        if( is_null($this->_lifetime) ) return;
        clearstatcache();
        $filemtime = @filemtime($fn);
        if( $filemtime < time() - $this->_lifetime ) @unlink($fn);
    }


    /**
     * @ignore
     */
    private function _write_cache_file($fn,$data)
    {
        @touch($fn);
        $fp = @fopen($fn,'r+');
        if( $fp ) {
            if( !$this->_flock($fp,self::LOCK_WRITE) ) {
                @fclose($fp);
                @unlink($fn);
                return FALSE;
            }
            else {
                if( is_array($data) || is_object($data) ) $data = self::KEY_SERIALIZED.serialize($data);
                @fwrite($fp,$data);
                $this->_flock($fp,self::LOCK_UNLOCK);
            }
            @fclose($fp);
            return TRUE;
        }
        return FALSE;
    }


    /**
     * @ignore
     */
    private function _auto_clean_files()
    {
        if( $this->_auto_cleaning > 0 && ($this->_auto_cleaning == 1 || mt_rand(1,$this->_auto_cleaning) == 1) ) {
            return $this->_clean_dir($this->_cache_dir);
        }
        return 0;
    }


    /**
     * @ignore
     */
    private function _clean_dir($dir,$group = '',$old = true)
    {
        $mask = $dir.'/dcache_*_*.cg';
        if( $group ) $mask = $dir.'/dcache_'.md5($group).'_*.cg';

        $files = glob($mask);
        if( !is_array($files) ) return 0;

        $nremoved = 0;
        foreach( $files as $file ) {
            if( is_file($file) ) {
                $del = false;
                if( $old == true ) {
                    if( !is_null($this->_lifetime) ) {
                        if( (time() - @filemtime($file)) > $this->_lifetime ) {
                            @unlink($file);
                            $nremoved++;
                        }
                    }
                }
                else {
                    @unlink($file);
                    $nremoved++;
                }
            }
        }

        return $nremoved;
    }
}

?>