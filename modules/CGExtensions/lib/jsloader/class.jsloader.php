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
 * A utility class to manage loading and combining javascript libraries and associated stylesheets.
 */
final class jsloader
{
    /**
     * @ignore
     */
    static $_required = array();

    /**
     * @ignore
     */
    static $_cache;

    /**
     * @ignore
     */
    private function __construct() {}

    /**
     * @ignore
     */
    private static function _load_cache()
    {
        class_exists('\CGExtensions\jsloader\libdefn');
        if( !is_array(self::$_cache) ) {
            $cache = array();
            $cache_key = 'c'.md5(__CLASS__);
            $tmp = \cms_siteprefs::get($cache_key);
            if( $tmp ) $cache = unserialize($tmp);
            self::$_cache = $cache;
        }
    }


    /**
     * @ignore
     */
    private static function _save_cache()
    {
        $cache_key = 'c'.md5(__CLASS__);
        \cms_siteprefs::set($cache_key,serialize(self::$_cache));
    }

    /**
     * @ignore
     */
    private static function _expand_filename($in)
    {
        $smarty = \Smarty_CMS::get_instance();
        $config = \cms_config::get_instance();
        $smarty->assign('root_url',$config['root_url']);
        $smarty->assign('root_path',$config['root_path']);
        return trim($smarty->fetch('string:'.$in));
    }

    /**
     * Register a library definition
     *
     * @param libdefn $rec The library definition to register.
     * @param bool $force Force the libdefn to be registered even if it already has been.
     */
    public static function register(libdefn $rec,$force = FALSE)
    {
        if( !$rec->valid() ) throw new \CmsInvalidDataException('attempt to register js lib with invalid libdefn object');

        $lib = self::_find_lib($rec->name);
        if( !$force && is_object($lib) && $lib == $rec ) return; // nothing to do.

        // add this item
        self::_load_cache();
        self::$_cache[$rec->name] = $rec;
        self::_save_cache();
    }

    /**
     * Unregister all javascript libraries associated with a module.
     *
     * @param string $module_name
     */
    public static function unregister_by_module($module_name)
    {
        self::_load_cache();
        $out = array();
        foreach( self::$_cache as $key => $rec ) {
            if( $rec->module != $module_name ) $out[$key] = $rec;
            return;
        }
        self::$_cache = $out;
        self::_save_cache();
    }

    /**
     * For this request, indicate that a javascript library is required.
     *
     * @param string $name
     */
    public static function require_lib($name)
    {
        if( !is_array($name) ) $name = explode(',',$name);
        foreach( $name as $one_name ) {
            $lib = self::_find_lib($one_name);
            if( !$lib ) throw new \Exception("Unknown required js lib $one_name");
            $obj = $lib;
        }
        self::$_required[] = $obj;
    }

    /**
     * Require an external javascript library.
     * [experimental]
     *
     * @param string $url
     */
    public static function require_jsext($url)
    {
        // externals cannot have dependencies...
        $obj = new stdClass;
        $obj->jsurl = $url;
        self::$_required[] = $obj;
    }

    /**
     * For this request, add a specified javascript file.
     *
     * @param string $file The complete pathname to the javascript file.
     * @param string[] $depends array of library names (must already be registered) that this file depends upon.
     */
    public static function add_jsfile($file,$depends = null)
    {
        if( !$file || !is_string($file) ) return;

        // assume full path
        $tryfiles = array($file);

        // assume relative to module directory
        $module_name = \cge_tmpdata::get('module');
        if( $module_name ) {
            $mod = \cms_utils::get_module($module_name);
            if( $mod ) $tryfiles[] = $mod->GetModulePath()."/$file";
        }

        // assume relative to uploads path
        $config = cmsms()->GetConfig();
        $tryfiles[] = $config['uploads_path']."/$file";

        // assume relative to root path
        $tryfiles[] = $config['root_path']."/$file";

        $fnd = null;
        foreach( $tryfiles as $fn ) {
            if( file_exists($fn) ) {
                $fnd = $fn;
                break;
            }
        }
        if( !$fnd ) throw new \CmsInvalidDataException("could not find jsfile $file in any of the searched directories");

        $obj = new \StdClass;
        $obj->jsfile = $fnd;
        if( $depends ) {
            if( !is_array($depends) ) $depends = array($depends);
            $obj->depends = $depends;
        }
        self::$_required[] = $obj;
    }

    /**
     * Add javascript code to the output for this request.
     *
     * @param string $code The javascript code (script tags are not required)
     * @param string[] $depends Array of required javascript libraries.
     */
    public static function add_js($code,$depends = null)
    {
        if( !$code || !is_string($code) ) return;

        // todo: remove script tags
        $obj = new \StdClass;
        $obj->code = $code;
        if( $depends ) {
            if( !is_array($depends) ) $depends = array($depends);
            $obj->depends = $depends;
        }
        self::$_required[] = $obj;
    }

    /**
     * @ignore
     */
    public static function require_css($name,$depends = null)
    {
        $obj = new \stdClass;
        $obj->cssname = trim($name);
        if( $depends ) {
            if( !is_array($depends) ) $depends = array($depends);
            $obj->depends = $depends;
        }
        self::$_required[] = $obj;
    }

    /**
     * @ignore
     */
    public static function require_cssext($url)
    {
        // externals cannot have dependencies...
        $obj = new stdClass;
        $obj->cssurl = $url;
        self::$_required[] = $obj;
    }


    /**
     * Add a css file to the output.
     *
     * @param string $file The filename.  If not an absolute path, then search for the file within the current module directory (if any), the uploads path, and then the root path.
     * @param array $depends Array of libraries that this css file depends upon.
     */
    public static function add_cssfile($file,$depends = null)
    {
        if( !$file || !is_string($file) ) return;

        // assume full path
        $tryfiles = array($file);

        // assume relative to module directory
        $module_name = \cge_tmpdata::get('module');
        if( $module_name ) {
            $mod = cms_utils::get_module($module_name);
            if( $mod ) $tryfiles[] = $mod->GetModulePath()."/$file";
        }

        // assume relative to uploads path
        $config = cmsms()->GetConfig();
        $tryfiles[] = $config['uploads_path']."/$file";
        $tryfiles[] = $config['root_path']."/$file";

        $fnd = null;
        foreach( $tryfiles as $fn ) {
            if( file_exists($fn) ) {
                $fnd = $fn;
                break;
            }
        }
        if( !$fnd ) throw new \CmsInvalidDataException("could not find jsfile $file in any of the searched directories");

        $obj = new \StdClass;
        $obj->cssfile = $fnd;
        if( $depends ) {
            if( !is_array($depends) ) $depends = array($depends);
            $obj->depends = $depends;
        }
        self::$_required[] = $obj;
    }

    /**
     * Add static css text to the output.
     *
     * @param string $styles The static CSS text (style tags are not needed).
     * @param array $depends A list of libraries that this css depends upon.
     */
    public static function add_css($styles,$depends = null)
    {
        if( !$styles || !is_string($styles) ) return;

        // todo: remove script tags
        $obj = new \StdClass;
        $obj->styles = $styles;
        if( $depends ) {
            if( !is_array($depends) ) $depends = array($depends);
            $obj->depends = $depends;
        }
        self::$_required[] = $obj;
    }

    /**
     * @ignore
     */
    private static function _find_lib($name)
    {
        self::_load_cache();
        if( isset(self::$_cache[$name]) ) return self::$_cache[$name];
    }

    /**
     * @ignore
     */
    private static function _resolve_dependencies($rec,&$out,$excludes)
    {
        self::_load_cache();
        if( isset($rec->lib) && in_array($rec->lib,$excludes) ) return;
        if( isset($rec->name) && in_array($rec->name,$excludes) ) return;
        if( $rec->module ) {
            $mod = \cms_utils::get_module($rec->module);
            if( !$mod ) throw new \Exception('Missing required module '.$rec->module);
        }

        // if this rec depends on something else
        if( isset($rec->depends) ) {
            $depends = $rec->depends;
            if( !is_array($depends) ) $depends = explode(',',$depends);
            foreach( $depends as $dependency ) {
                $dep = self::_find_lib($dependency);
                if( !$dep ) throw new \Exception('Missing js dependency: '.$dependency);
                self::_resolve_dependencies($dep,$out,$excludes);
            }
        }

        // now handle this item.
        $sig = md5(serialize($rec));
        if( !isset($out[$sig]) ) $out[$sig] = $rec;
    }

    /**
     * Render the output javascript and stylesheets into cachable files
     * and output the appropriate HTML tags.
     *
     * @param array $opts Options for this method (for further reference, see the {cgjs_render} smarty tag.
     * @return string HTML output code.
     */
    public static function render($opts = null)
    {
        if( count(self::$_required) == 0 ) return; // nothing to do.

        // process options
        $options = array();
        $options['excludes'] = array();
        if( !cmsms()->is_frontend_request() ) {
            // the cmsms admin console includes versions of these.
            $excludes = array();
            $excludes[] = 'jquery';
            $excludes[] = 'ui';
            $excludes[] = 'fileupload';
            $options['excludes'] = $excludes;
        }
        if( is_array($opts) ) $options = array_merge_recursive($options,$opts);
        if( isset($options['no_jquery']) && !in_array('jquery',$options['excludes']) ) {
            $options['excludes'][] = 'jquery';
        }
        if( isset($options['excludes']) && count($options['excludes']) ) {
            // clean up the excludes
            $out = array();
            foreach( $options['excludes'] as &$str ) {
                $str = strtolower(trim($str));
                if( !$str ) continue;
                if( !in_array($str,$out) ) $out[] = $str;
            }
            $options['excludes'] = $out;
        }
        $options['lang'] = \CmsNlsOperations::get_current_language();

        // expand some options to simple variables.
        $config = cmsms()->GetConfig();
        $cache_lifetime = (int)\cge_utils::get_param($config,'cgejs_cachelife',24);
        $cache_lifetime = (isset($options['cache_lifetime'])) ? (int)$options['cache_lifetime'] : $cache_lifetime;
        $cache_lifetime = max($cache_lifetime,1);
        $nocache = \cge_utils::get_param($config,'cgejs_nocache',0);
        $nocache = (isset($options['no_cache']) || $nocache)?TRUE:$nocache;
        $nominify = \cge_utils::get_param($config,'cgejs_nominify',0);
        $nominify = (isset($options['nominify']) || $nominify)?TRUE:$nominify;
        $nocsssmarty = (isset($options['nocsssmarty']) || $nominify)?TRUE:$nocache;
        $addkey = \cge_utils::get_param($options,'addkey','');
        $do_js = (isset($options['no_js']))?FALSE:TRUE;
        $do_css = (isset($options['no_css']))?FALSE:TRUE;
        $js_fmt = '<script type="text/javascript" src="%s"></script>';
        $css_fmt = '<link type="text/css" rel="stylesheet" href="%s"/>';
        if( !$nocache && !$nominify ) require_once(dirname(dirname(__FILE__)).'/jsmin.php');

        $get_relative_url = function($filename) {
            $config = cmsms()->GetConfig();
            $relative_url = '';
            if( startswith($filename,$config['root_path']) ) {
                $relative_url = str_replace($config['root_path'],$config['root_url'],dirname($filename));
                if( !endswith($relative_url,'/') ) $relative_url .= '/';
                if( startswith($relative_url,'http:') ) $relative_url = substr($relative_url,5);
                if( startswith($relative_url,'https:') ) $relative_url = substr($relative_url,6);
            }
            return $relative_url;
        };

        $fix_css_urls = function($css,$url_prefix) {
            $css_search = '#url\(\s*[\'"]?(.*?)[\'"]?\s*\)#';
            $css_url_fix = function($matches) use ($url_prefix) {
                if( startswith($matches[1],'data:') ) return $matches[0];
                if( startswith($matches[1],'http:') ) return $matches[0];
                if( startswith($matches[1],'https:') ) return $matches[0];
                if( startswith($matches[1],'//') ) return $matches[0];
                //$str = substr($matches[1],0,-1);
                $str = $matches[1];
                return "url('{$url_prefix}{$str}')";
            };
            $out = preg_replace_callback($css_search,$css_url_fix,$css);
            return $out;
        };

        // determine if we have to process all this cruft (which could potentially be very expensive)
        $sig = md5(serialize(self::$_required).serialize($options));
        $cache_js = TMP_CACHE_LOCATION."/cgejs_{$sig}.js";
        $cache_css = TMP_CACHE_LOCATION."/cgejs_{$sig}.css";
        $have_js = $have_css = FALSE;
        $do_js2 = $do_css2 = TRUE;
        $do_processing = TRUE;
        if( !$nocache ) {
            $etime = time() - $cache_lifetime * 3600;
            if( file_exists($cache_js) ) {
                $mtime1 = @filemtime($cache_js);
                $have_js = TRUE;
                if( $mtime1 > $etime ) {
                    // cache too olo, forced to rebuild
                    $have_js = FALSE;
                    $do_js2 = TRUE;
                }
            }

            if( file_exists($cache_css) ) {
                $mtime2 = @filemtime($cache_css);
                $have_css = TRUE;
                if( $mtime2 > $etime ) {
                    // cache too old, forced to rebuild
                    $have_css = FALSE;
                    $do_css2 = TRUE;
                }
            }
        }

        if( $do_js2 || $do_css2 ) {
            // okay, we have work to do.
            $required = array();

            // now expand all our dependencies.
            $list_0 = array();
            foreach( self::$_required as $rec ) {
                if( isset($rec->depends) ) {
                    self::_resolve_dependencies($rec,$list_0,$options['excludes']);
                }
                else {
                    $sig = md5(serialize($rec));
                    $list_0[$sig] = $rec;
                }
            }

            // now check for callback items
            // and get their code... this may be an expensive process
            // note: may also have dependencies
            $list = array();
            foreach( $list_0 as $rec ) {
                if( isset($rec->callback) ) {
                    $tmp = call_user_func($rec->callback,$rec->name);
                    if( is_object($tmp) && (isset($tmp->code) || isset($tmp->styles)) ) {
                        $list[] = $tmp;
                    }
                }
                else {
                    $list[] = $rec;
                }
            }

            //
            // process js
            //
            if( $do_js && $do_js2 && $list && count($list) ) {
                $txt = null;
                foreach( $list as $rec ) {
                    $js = null;

                    // get js for this item
                    if( isset($rec->jsfile) ) {
                        $jsfile = $rec->jsfile;
                        if( !is_array($jsfile) ) $jsfile = array($jsfile);
                        foreach( $jsfile as $one_file ) {
                            $one_file = self::_expand_filename($one_file);
                            if( is_file($one_file) ) $js .= @file_get_contents($one_file);
                        }
                    }
                    else if( isset($rec->code) ) {
                        $js = $rec->code;
                    }

                    if( $js ) $txt .= $js."\n";
                }

                if( $txt ) {
                    $have_js = TRUE;
                    file_put_contents($cache_js,$txt);
                }
            } // do_js

            //
            // process css
            //
            if( $do_css && $do_css2 && $list && count($list) ) {
                $txt = null;
                foreach( $list as $rec ) {
                    $css = null;

                    if( isset($rec->cssfile) ) {
                        $cssfile = $rec->cssfile;
                        if( !is_array($cssfile) ) $cssfile = array($cssfile);
                        foreach( $cssfile as $one_file ) {
                            $one_file = self::_expand_filename($one_file);
                            $tmp = file_get_contents($one_file);
                            $relative_url = $get_relative_url($one_file);
                            $tmp = $fix_css_urls($tmp,$relative_url);
                            $css .= $tmp;
                        }
                    }
                    else if( isset($rec->cssname) ) {
                        if( version_compare(CMS_VERSION,'1.99-alpha0') < 0 ) {
                            $query = 'SELECT css_id, css_name, css_text FROM '.cms_db_prefix().'css WHERE css_name = ?';
                            $db = cmsms()->GetDb();
                            $row = $db->GetRow($query,array($rec->cssname));
                            if( !is_array($row) ) return;

                            $css = trim($row['css_text']);
                        }
                        else {
                            $css = CmsLayoutStylesheet::load($rec->cssname)->get_content();
                        }
                    }
                    else if( isset($rec->styles) ) {
                        $css = $rec->styles;
                    }

                    // todo: fix up relative urls in css

                    if( $css ) $txt .= $css."\n";
                }

                // process this stuff through smarty with [[ and ]] delimiters
                // todo: or lesscss
                if( !$nocsssmarty ) {
                    // sorry, not done yet.
                }

                //if( !$nominify ) $txt = JSMin::minify($txt);
                if( $txt ) {
                    $have_css = TRUE;
                    file_put_contents($cache_css,$txt);
                }

            } // do_css
        } // do processing

        // do the output.
        if( $nocache ) {
            $cache_js .= '?_t='.time();
            $cache_css .= '?_t='.time();
        }

        $out = null;
        if( $have_js ) {
            $cache_url = $config['root_url'].'/tmp/cache/'.basename($cache_js);
            $out .= trim(sprintf($js_fmt,$cache_url))."\n";
        }

        if( $have_css ) {
            $cache_url = $config['root_url'].'/tmp/cache/'.basename($cache_css);
            $out .= trim(sprintf($css_fmt,$cache_url))."\n";
        }

        // all freaking done
        return $out;
    }
}

?>
