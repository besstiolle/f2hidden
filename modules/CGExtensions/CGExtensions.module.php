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
 * A base class for all CMSMS modules written by me to provide optimizations and
 * conveniences that are not built into CMSMS.
 *
 * Some of these functions and tools have, over time made their way in one way, shape
 * or form into the core.  However, many have not, and will not.
 *
 * @package CGExtensions
 */

if( defined('CGEXTENSIONS_TABLE_COUNTRIES') ) return;

/**
 * @ignore
 */
define('CGEXTENSIONS_TABLE_COUNTRIES',cms_db_prefix().'module_cge_countries');

/**
 * @ignore
 */
define('CGEXTENSIONS_TABLE_STATES',cms_db_prefix().'module_cge_states');

/**
 * @ignore
 */
define('CGEXTENSIONS_TABLE_ASSOCDATA',cms_db_prefix().'module_cge_assocdata');

/**
 * A base class for all CMSMS modules written by me to provide optimizations and
 * conveniences that are not built into CMSMS.
 *
 * @package CGExtensions
 */
class CGExtensions extends CMSModule
{
    /**
     * @ignore
     */
    private static $_initialized;

    /**
     * @ignore
     */
    private $_obj = null;

    /**
     * @ignore
     */
    public $_colors;

    /**
     * @ignore
     */
    public $_actionid = null;

    /**
     * @ignore
     */
    public $_image_directories;

    /**
     * @ignore
     */
    public $_current_tab;

    /**
     * @ignore
     */
    public $_current_action;

    /**
     * @ignore
     */
    public $_errormsg;

    /**
     * @ignore
     */
    public $_messages;

    /**
     * @ignore
     */
    public $_returnid;

    /**
     * The constructor.
     * This method does numerous things, including setup an extended autoloader,
     * create defines for the module itself.  i.e: MOD_CGEXTENSIONS, or MOD_FRONTENDUSERS.
     * sets up a built in cache driver for temporarily caching data.
     * and register numerous smarty plugins (see the documentation for those).
     */
    public function __construct()
    {
        spl_autoload_register(array($this,'autoload'));
        parent::__construct();

        global $CMS_INSTALL_PAGE, $CMS_PHAR_INSTALL;
        if( isset($CMS_INSTALL_PAGE) || isset($CMS_PHAR_INSTALL) ) return;

        $class = get_class($this);
        if( !defined('MOD_'.strtoupper($class)) ) {
            /**
             * @ignore
             */
            define('MOD_'.strtoupper($class),$class);
        }

        if( self::$_initialized ) return;
        self::$_initialized = TRUE;

        // from here down only happens once per request.

        // setup caching
        if( $class == 'CGExtensions' && !is_object(cms_cache_handler::get_instance()->get_driver()) ) {
            $lifetime = (int)$this->GetPreference('cache_lifetime',300);
            $filelock = (int)$this->GetPreference('cache_filelock',1);
            $autoclean = (int)$this->GetPreference('cache_autoclean',1);
            if( $autoclean ) {
                // autoclean is enabled... but we don't want to search through the directory for files to delete
                // on each request... so we just do that once per interval.
                $tmp = $this->GetPreference('cache_autoclean_last',0);
                if( (time() - $tmp) < $lifetime ) {
                    $autoclean = 0;
                }
                else {
                    $autoclean = 1;
                    $this->SetPreference('cache_autoclean_last',time());
                }
            }
            $driver = new cms_filecache_driver(array('cache_dir'=>TMP_CACHE_LOCATION,'lifetime'=>$lifetime,'locking'=>$filelock,
                                                     'auto_cleaning'=>$autoclean));
            cms_cache_handler::get_instance()->set_driver($driver);
        }

        $smarty = cmsms()->GetSmarty();
        if( !$smarty ) return;

        $smarty->register_function('cge_yesno_options','cge_smarty_plugins::smarty_function_cge_yesno_options');
        $smarty->register_function('cge_have_module', array('cge_smarty_plugins','smarty_function_have_module'));

        $smarty->register_block('cgerror', array('cge_smarty_plugins','blockDisplayError'));
        $smarty->register_block('jsmin', array('cge_smarty_plugins','jsmin'));

        $smarty->register_function('cge_cached_url',array('cge_smarty_plugins','cge_cached_url'));
        $smarty->register_function('cgimage',array('cge_smarty_plugins','smarty_function_cgimage'));
        $smarty->register_function('cge_helptag',array('cge_smarty_plugins','smarty_function_helptag'));
        $smarty->register_function('cge_helphandler',array('cge_smarty_plugins','smarty_function_helphandler'));
        $smarty->register_function('cge_helpcontent',array('cge_smarty_plugins','smarty_function_helpcontent'));
        $smarty->register_function('cge_state_options', array('cge_smarty_plugins','smarty_function_cge_state_options'));
        $smarty->register_function('cge_country_options', array('cge_smarty_plugins','smarty_function_cge_country_options'));
        $smarty->register_function('cge_textarea', array('cge_smarty_plugins','smarty_function_cge_textarea'));
        $smarty->register_function('get_current_url', array('cge_smarty_plugins','smarty_function_get_current_url'));
        $smarty->register_function('cge_str_to_assoc',array('cge_smarty_plugins','smarty_function_str_to_assoc'));
        $smarty->register_modifier('rfc_date', array('cge_smarty_plugins','smarty_modifier_rfc_date'));
        $smarty->register_modifier('time_fmt', array('cge_smarty_plugins','smarty_modifier_time_fmt'));
        $smarty->register_modifier('cge_entity_decode', array('cge_smarty_plugins','smarty_modifier_cge_entity_decode'));
        $smarty->register_compiler_function('cge_cache',array('cge_smarty_plugins','cache_start'));
        $smarty->register_compiler_function('cge_cacheclose',array('cge_smarty_plugins','cache_end'));

        $smarty->register_function('cge_module_hint',array('cge_smarty_plugins','cge_module_hint'));
        $smarty->register_function('cge_file_list',array('cge_smarty_plugins','cge_file_list'));
        $smarty->register_function('cge_image_list',array('cge_smarty_plugins','cge_image_list'));
        $smarty->register_function('cge_array_set',array('cge_smarty_plugins','cge_array_set'));
        $smarty->register_function('cge_array_erase',array('cge_smarty_plugins','cge_array_erase'));
        $smarty->register_function('cge_array_get',array('cge_smarty_plugins','cge_array_get'));
        $smarty->register_function('cge_array_getall',array('cge_smarty_plugins','cge_array_getall'));
        $smarty->register_function('cge_admin_error',array('cge_smarty_plugins','cge_admin_error'));
        $smarty->register_function('cge_wysiwyg',array('cge_smarty_plugins','cge_wysiwyg'));
        $smarty->register_modifier('cge_createurl',array('cge_smarty_plugins','smarty_modifier_createurl'));
        $smarty->register_function('cge_setlist',array('cge_smarty_plugins','cge_setlist'));
        $smarty->register_function('cge_unsetlist',array('cge_smarty_plugins','cge_unsetlist'));
        $smarty->register_function('cge_message',array('cge_smarty_plugins','cge_message'));

        $smarty->register_function('cge_isbot',array('cge_smarty_plugins','cge_isbot'));
        $smarty->register_function('cge_is_smartphone',array('cge_smarty_plugins','cge_is_smartphone'));
        $smarty->register_function('cge_getbrowser',array('cge_smarty_plugins','cge_get_browser'));
        $smarty->register_function('cge_isie',array('cge_smarty_plugins','cge_isie'));

        $smarty->register_function('cge_content_type',array('cge_smarty_plugins','cge_content_type'));
        $smarty->register_function('cge_start_tabs',array('cge_smarty_plugins','cge_start_tabs'));
        $smarty->register_function('cge_end_tabs',array('cge_smarty_plugins','cge_end_tabs'));
        $smarty->register_function('cge_tabheader',array('cge_smarty_plugins','cge_tabheader'));
        $smarty->register_function('cge_tabcontent_start',array('cge_smarty_plugins','cge_tabcontent_start'));
        $smarty->register_function('cge_tabcontent_end',array('cge_smarty_plugins','cge_tabcontent_end'));

        $smarty->register_function('cgjs_render',array('cge_smarty_plugins','cgjs_render'));
        $smarty->register_function('cgjs_require',array('cge_smarty_plugins','cgjs_require'));
        $smarty->register_block('cgjs_add',array('cge_smarty_plugins','cgjs_add'));
        $smarty->register_block('cgcss_add',array('cge_smarty_plugins','cgcss_add'));

        $db = cms_utils::get_db();
        if( is_object($db) ) {
            $query = 'SET @CG_ZEROTIME = NOW() - INTERVAL 150 YEAR,@CG_FUTURETIME = NOW() + INTERVAL 5 YEAR';
            $db->Execute($query);
        }
    }

    /**
     * An extended autoload method.
     * Search for classes a <module>/lib/class.classname.php file.
     * or for interfaces in a <module>/lib/interface.classname.php file.
     * or as a last ditch effort, for simple classes in the <module>/lib/extraclasses.php file.
     * This method also supports namespaces,  including <module> and <module>/sub1/sub2 which should exist in files as described above.
     * in subdirectories below the <module>/lib directory.
     *
     * @internal
     * @param string $classname
     */
    public function autoload($classname)
    {
        if( !is_object($this) ) return FALSE;

        // check for classes.
        $path = $this->GetModulePath().'/lib';
        if( strpos($classname,'\\') !== FALSE ) {
            $t_path = str_replace('\\','/',$classname);
            if( startswith( $t_path, $this->GetName().'/' ) ) {
                $classname = basename($t_path);
                $t_path = dirname($t_path);
                $t_path = substr($t_path,strlen($this->GetName())+1);
                $path = $this->GetModulePath().'/lib/'.$t_path;
            }
        }

        $fn = $path."/class.{$classname}.php";
        if( file_exists($fn) ) {
            require_once($fn);
            return TRUE;
        }

        // check for interfaces
        $fn = $path."/interface.{$classname}.php";
        if( file_exists($fn) ) {
            require_once($fn);
            return TRUE;
        }

        // check for a master file
        $fn = $this->GetModulePath()."/lib/extraclasses.php";
        if( file_exists($fn) ) {
            require_once($fn);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * @ignore
     */
    public function &__get($key)
    {
        switch( $key ) {
        case 'db':
            return cge_utils::get_db();

        default:
            $out = parent::__get($key);
            return $out;
        }
    }

    /**
     * Set parameters for this module.
     *
     * @deprecated
     * @see CMSModule::SetParameters()
     */
    public function SetParameters()
    {
        parent::SetParameters();

        $this->RestrictUnknownParams();
        $this->SetParameterType('cge_msg',CLEAN_STRING);
        $this->SetParameterType('cge_error',CLEAN_INT);
        $this->SetParameterType('nocache',CLEAN_INT);
        $this->CreateParameter('nocache',0,$this->Lang('param_nocache'));
    }


    /**
     * @ignore
     * @deprecated
     */
    private function _load_main()
    {
        if( is_object($this->_obj) ) return;
        require_once(__DIR__.'/class.cgextensions.tools.php');
        $this->_obj = new cgextensions_tools($this);
    }


    /**
     * @ignore
     * @deprecated
     */
    private function _load_form()
    {
        require_once(__DIR__.'/form_tools.php');
    }

    /**
     * Get an internal datastore object for keeping key/value data associated with this module.
     *
     public function &GetDatastore()
     {
     $this->_load_datastore();
     return $this->_datastore_obj;
     }
    */

    /**
     * The name of this module.  This is an abstract method.
     *
     * @see CMSModule::GetName()
     * @abstract
     * @return string
     */
    public function GetName() { return 'CGExtensions'; }

    /**
     * The Friendly name for this module.  For use in the admin navigation.
     *
     * @see CMSModule::GetFriendlyName()
     * @abstract
     * @return string
     */
    public function GetFriendlyName() { return $this->Lang('friendlyname'); }

    /**
     * Return the version of this module.
     *
     * @see CMSModule::GetVersion()
     * @abstract
     * @return string
     */
    public function GetVersion() { return '1.44.3'; }

    /**
     * Return the help of this module.
     *
     * @see CMSModule::GetHelp()
     * @abstract
     * @return string
     */
    public function GetHelp() { return file_get_contents(__DIR__.'/help.inc'); }

    /**
     * Return the Author of this module.
     *
     * @see CMSModule::GetAuthor()
     * @abstract
     * @return string
     */
    public function GetAuthor() { return 'calguy1000'; }

    /**
     * Return the email address for the author of this module.
     *
     * @see CMSModule::GetAuthorEmail()
     * @abstract
     * @return string
     */
    public function GetAuthorEmail() { return 'calguy1000@cmsmadesimple.org'; }

    /**
     * Return the changelog for this module.
     *
     * @see CMSModule::GetChangeLog()
     * @abstract
     * @return string
     */
    public function GetChangeLog() { return file_get_contents(__DIR__.'/changelog.inc'); }

    /**
     * Return if this is a plugin module (for the frontend of the website) or not.
     *
     * @see CMSModule::IsPluginModule()
     * @abstract
     * @return bool
     */
    public function IsPluginModule() { return true; }

    /**
     * Return if this module has an admin section.
     *
     * @see CMSModule::HasAdmin()
     * @abstract
     * @return string
     */
    public function HasAdmin() { return true; }

    /**
     * Return if this module handles events.
     *
     * @see CMSModule::HandlesEvents()
     * @abstract
     * @return string
     */
    public function HandlesEvents() { return true; }

    /**
     * Get the section of the admin navigation that this module belongs to.
     *
     * @abstract
     * @return string
     */
    public function GetAdminSection() { return 'extensions'; }

    /**
     * Get a human readable description for this module.
     *
     * @abstract
     * @return string
     */
    public function GetAdminDescription() { return $this->Lang('moddescription'); }

    /**
     * Get a hash containing dependent modules, and their minimum versions.
     *
     * @abstract
     * @return string
     */
    public function GetDependencies() { return array(); }

    /**
     * Display a custom message after the module has been installed.
     *
     * @abstract
     * @return string
     */
    public function InstallPostMessage() { return $this->Lang('postinstall'); }

    /**
     * Return the minimum CMSMS version that this module is compatible with.
     *
     * @abstract
     * @return string
     */
    public function MinimumCMSVersion() { return '1.11.9'; }

    /**
     * Return a message to display after the module has been uninstalled.
     *
     * @abstract
     * @return string
     */
    public function UninstallPostMessage() { return $this->Lang('postuninstall'); }

    /**
     * Test if this module is visible in the admin navigation to the currently logged in admin user.
     *
     * @abstract
     * @return bool
     */
    public function VisibleToAdminUser()
    {
        return $this->CheckPermission('Modify Site Preferences') ||  $this->CheckPermission('Modify Templates');
    }

    /**
     * Retrieve some HTML to be output in all admin requests for this module (and its descendants).
     * By default this module calls the jsloader::render method,  and includes some standard styles
     *
     * @abstract
     * @see \CGExtensions\jsloader\jsloader::render();
     * @return bool
     */
    public function GetHeaderHTML()
    {
        $out = \CGExtensions\jsloader\jsloader::render();
        $mod = cms_utils::get_module('CGExtensions');
        $css = $mod->GetModuleURLPath().'/css/admin_styles.css';
        $out .= '<link rel="stylesheet" href="'.$css.'"/>'."\n";
        return $out;
    }


    /**
     * A replacement for the built in DoAction method
     * For CGExtensions derived modules some  builtin smarty variables are created
     * module hints are handled,  and input type=image values are corrected in input parameters.
     *
     * this method also handles setting the active tab, and displaying any messages or errors
     * set with the SetError or SetMessage methods.
     *
     * This method is called automatically by the system based on the incoming request, and the page template.
     * It should almost never be called manually.
     *
     * @see SetError()
     * @see SetMessage()
     * @see SetCurrentTab()
     * @see RedirectToTab()
     * @param string $name the action name
     * @param string $id The module action id
     * @param array  $params The module parameters
     * @param int    $returnid  The page that will contain the HTML results.  This is empty for admin requests.
     */
    public function DoAction($name,$id,$params,$returnid='')
    {
        if( !method_exists($this,'set_action_id') && $this->GetName() != 'CGExtensions' ) {
            die('FATAL ERROR: A module derived from CGExtensions is not handling the set_action_id method');
        }
        $this->set_action_id($id);

        // handle the stupid input type='image' problem.
        foreach( $params as $key => $value ) {
            if( endswith($key,'_x') ) {
                $base = substr($key,0,strlen($key)-2);
                if( isset($params[$base.'_y']) && !isset($params[$base]) ) $params[$base] = $base;
            }
        }

        // handle module hints
        $hints = cms_utils::get_app_data('__MODULE_HINT__'.$this->GetName());
        if( is_array($hints) ) {
            foreach( $hints as $key => $value ) {
                if( isset($params[$key]) ) continue;
                $params[$key] = $value;
            }
        }

        $smarty = cmsms()->GetSmarty();
        $smarty->assign('actionid',$id);
        $smarty->assign('actionparams',$params);
        $smarty->assign('returnid',$returnid);
        $smarty->assign('mod',$this);
        $smarty->assign($this->GetName(),$this);
        cge_tmpdata::set('module',$this->GetName());

        if( $returnid == '' ) {
            // admin action
            if( isset($params['cg_activetab']) ) {
                $this->_current_tab = trim($params['cg_activetab']);
                unset($params['cg_activetab']);
            }
            if( isset($params['cg_error']) ) {
                $this->_errormsg = explode(':err:',$params['cg_error']);
                unset($params['cg_error']);
            }
            if( isset($params['cg_message']) ) {
                $this->_messages = explode(':msg:',$params['cg_message']);
                unset($params['cg_message']);
            }

            $this->DisplayErrors();
            $this->DisplayMessages();
        }

        parent::DoAction($name,$id,$params,$returnid);
    }


    /**
     * A convenience method to encrypt some data
     *
     * @see cge_encrypt
     * @param string $key The encryption key
     * @param string $data The data to encrypt
     * @return string The encrypted data
     */
    function encrypt($key,$data)
    {
        return cge_encrypt::encrypt($key,$data);
    }


    /**
     * A convenience method to decrypt some data
     *
     * @see cge_encrypt
     * @param string $key The encryption key
     * @param string $data The data to decrypt
     * @return string The derypted data
     */
    function decrypt($key,$data)
    {
        return cge_encrypt::decrypt($key,$data);
    }


    /**
     * A convenience function to create a url for a module action.
     * This method is deprecated as the CMSModule::create_url method replaces it.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $action The module action
     * @param string $returnid The page that the url will refer to.  This is empty for admin requests
     * @param array  $params Module parameters
     * @param bool   $inline For frontend requests only dicates wether this url should be inline only.
     * @param string $prettyurl
     */
    function CreateURL($id,$action,$returnid,$params=array(),$inline=false,$prettyurl='')
    {
        $this->_load_main();
        return $this->_obj->__CreatePrettyLink($id,$action,$returnid,'',$params,'',true,$inline,'',false,$prettyurl);
    }


  /* ======================================== */
  /* FORM FUNCTIONS                           */
  /* ======================================== */

    /**
     * A convenience method to create a control that contains a 'sortable list'.
     * The output control is translated, and interactive and suitable for use in forms.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name The input element name
     * @param array $items An associative array of the items for this list.
     * @param string $selected A comma separated string of selected item keys
     * @param bool $allowduplicates
     * @param int $max_selected The maximum number of items that can be selected
     * @param string $template Specify an alternate template for the sortable list control
     * @param string $label_left  A label for the left column.
     * @param string $label_right A label for the right column.
     * @return string
     */
    function CreateSortableListArea($id,$name,$items, $selected = '', $allowduplicates = true, $max_selected = -1,
                                    $template = '', $label_left = '', $label_right = '')
    {
        $cge = $this->GetModuleInstance('CGExtensions');
        if( empty($label_left) ) $label_left = $cge->Lang('selected');
        if( empty($label_right) ) $label_right = $cge->Lang('available');
        $smarty = cmsms()->GetSmarty();
        if( !empty($selected) ) {
            $sel = explode(',',$selected);
            $tmp = array();
            foreach($sel as $theid) {
                if( array_key_exists($theid,$items) ) $tmp[$theid] = $items[$theid];
            }
            $smarty->assign('selectarea_selected_str',$selected);
            $smarty->assign('selectarea_selected',$tmp);
        }
        $smarty->assign_by_ref('cge',$cge);
        $smarty->assign('max_selected',$max_selected);
        $smarty->assign('label_left',$label_left);
        $smarty->assign('label_right',$label_right);
        $smarty->assign('selectarea_masterlist',$items);
        $smarty->assign('selectarea_prefix',$id.$name);
        if( $allowduplicates ) $allowduplicates = 1; else $allowduplicates = 0;
        $smarty->assign('allowduplicates',$allowduplicates);
        $smarty->assign('upstr',$cge->Lang('up'));
        $smarty->assign('downstr',$cge->Lang('down'));
        if( empty($template) ) $template = $cge->GetPreference('dflt_sortablelist_template');
        return $cge->ProcessTemplateFromDatabase('sortablelists_'.$template);
    }


    /**
     * Create a translated Yes/No dropdown.
     * The output control is translated, and suitable for use in forms.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name for the input element
     * @param int    $selectedvalue The selected value (0 == no, 1 == yes)
     * @param string $addtext
     * @return string
     */
    function CreateInputYesNoDropdown($id,$name,$selectedvalue='',$addtext='')
    {
        $this->_load_form();
        return cge_CreateInputYesNoDropdown($this,$id,$name,$selectedvalue,$addtext);
    }

    /**
     * Create a custom submit button.
     * The output control is translated, and suitable for use in forms.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name for the input element
     * @param string $value The value for the submit button
     * @param string $addtext Additional text for the tag
     * @param string $image an optional image path
     * @param string $confirmtext Optional confirmation text
     * @param string $class Optional value for the class attribute
     * @return string
     */
    function CGCreateInputSubmit($id,$name,$value='',$addtext='',$image='', $confirmtext='',$class='')
    {
        $this->_load_form();
        return cge_CreateInputSubmit($this,$id,$name,$value,$addtext,$image,$confirmtext,$class);
    }


    /**
     * Create a custom checkbox.
     * This is similar to the standard checkbox but has a hidden field with the same name
     * before it so that some value for this field is always returned to the form handler.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name for the input element
     * @param string $value The value for the checkbox
     * @param string $selectedvalue The current value of the field.
     * @param string $addtext Additional text for the tag
     * @return string
     */
    function CreateInputCheckbox($id,$name,$value='',$selectedvalue='', $addtext='')
    {
        $this->_load_form();
        return cge_CreateInputCheckbox($this,$id,$name,$value,$selectedvalue,$addtext);
    }


    /**
     * A Convenience function for creating form tags.
     * This method re-organises some of the parameters of the original CreateFormStart method
     * and handles current tab functionalty, and sets the encoding type of the form to multipart/form-data
     *
     * This method is deprecated and will be replaced in CMSMS 2.0 by the core {form_start} tag.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $action the destination action
     * @param string $returnid The destination pagpe for the action handler.  Empty for admin requests
     * @param array  $params additional parameters to be passed with the form
     * @param bool   $inline wether this is an inline form request (output will replace module tag rather than the entire content section of the template.
     * @param string $method The form method.
     * @param string $enctype The form encoding type
     * @param string $idsuffix
     * @param string $extra Extra text for thhe form tag
     * @return string
     */
    function CGCreateFormStart($id,$action='default',$returnid='',$params=array(),$inline=false,$method='post',
                               $enctype='',$idsuffix='',$extra='')
    {
        if( !empty($this->_current_tab) ) $params['cg_activetab'] = $this->_current_tab;
        if( $enctype == '' ) $enctype = 'multipart/form-data';
        return $this->CreateFormStart($id,$action,$returnid,$method,$enctype,$inline,$idsuffix,$params,$extra);
    }


    /**
     * A convenience function for creating a frontend form
     * This method re-organises some of the parameters of the original CreateFormStart method
     * and sets the encoding type of the form to multipart/form-data
     *
     * This method is deprecated and will be replaced in CMSMS 2.0 by the core {form_start} tag.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $action the destination action
     * @param string $returnid The destination pagpe for the action handler.  Empty for admin requests
     * @param array  $params additional parameters to be passed with the form
     * @param bool   $inline wether this is an inline form request (output will replace module tag rather than the entire content section of the template.
     * @param string $method The form method.
     * @param string $enctype The form encoding type
     * @param string $idsuffix
     * @param string $extra Extra text for thhe form tag
     * @return string
     */
    function CGCreateFrontendFormStart($id,$action='default',$returnid='', $params=array(),$inline=true,$method='post',
                                       $enctype='',$idsuffix='',$extra='')
    {
        $this->_load_form();
        return $this->CreateFrontendFormStart($id,$returnid,$action,$method,$enctype,$inline,$idsuffix,$params,$extra);
    }


    /**
     * A convenience method to create a hidden input element for forms.
     * This method is deprecated.  It is best to assign all data to smarty and then create input elements as necessary in the smarty template.
     *
     * @deprecated
     * @param string $id the module action id
     * @param string $name The name of the input element
     * @param string $value The value of the input element
     * @param string $addtext Additional text for the tag
     * @param string $delim the delimiter for value separation.
     * @return string
     */
    function CreateInputHidden($id,$name,$value='',$addtext='',$delim=',')
    {
        $this->_load_form();
        return cge_CreateInputHidden($this,$id,$name,$value,$addtext,$delim);
    }


    /**
     * For admin requests only, pass variables so that the specified tab will be displayed
     * by default in the resulting action.
     *
     * @param string $id the module action id.  For admin requests this is usually 'm1_'
     * @param string $tab The name of the parameter
     * @param string $params Extra parameters for the request
     * @param string $action The designated module action.  If none is specified 'defaultadmin' is assumed.
     */
    function RedirectToTab( $id = '', $tab = '', $params = '', $action = '' )
    {
        if( $id == '' ) $id = 'm1_';
        $this->_load_main();
        return $this->_obj->RedirectToTab($id,$tab,$params,$action);
    }


    /**
     * Redirect to a specified module action.
     * This method is usable both for admin and frontend requests.
     *
     * @param string $id the module action id.  For admin requests this is usually 'm1_'
     * @param string $action The designated module action.  If none is specified 'defaultadmin' is assumed.
     * @param int $returnid The destination page.  empty for admin requests.
     * @param array $params Extra parameters for the URL
     * @param bool $inline Wether the output should be an inline URL or not ??
     */
    function Redirect($id,$action,$returnid='',$params = array(),$inline=false)
    {
        $parms = array();
        if( is_array( $params ) ) $parms = $params;
        if( is_array($this->_errormsg) && count($this->_errormsg) ) $parms['cg_error'] = implode(':err:',$this->_errormsg);
        if( is_array($this->_messages) && count($this->_messages) ) $parms['cg_message'] = implode(':msg:',$this->_messages);

        parent::Redirect( $id, $action, $returnid, $parms, $inline );
    }

    /**
     * @ignore
     */
    function CGRedirect($id,$action,$returnid='',$params=array(),$inline = false)
    {
        $this->Redirect($id,$action,$returnid,$params,$inline);
    }


    /**
     * Test if the current code is handling an admin action or a frontend action
     *
     * @return bool True for an admin action, false otherwise.
     */
    function IsAdminAction()
    {
        if( cmsms()->test_state(CmsApp::STATE_ADMIN_PAGE) && !cmsms()->test_state(CmsApp::STATE_INSTALL) &&
            !cmsms()->test_state(CmsApp::STATE_STYLESHEET) ) {
            return TRUE;
        }
        return FALSE;
    }


    /**
     * Set an error to display on the next admin request from a CGExtensions derived module.
     * The error message will be carried on to the next admin request and displayed.
     *
     * @param string $str The error message.
     */
    function SetError($str)
    {
        if( !is_array( $this->_errormsg ) ) $this->_errormsg = array();
        if( !is_array($str) ) $str = array($str);
        $this->_errormsg = array_merge($this->_errormsg,$str);
    }


    /**
     * Set a message to display on the next admin request from a CGExtensions derived module.
     * The message will be carried on to the next admin request and displayed.
     *
     * @param string $str The informational message to display.
     */
    function SetMessage($str)
    {
        if( !is_array( $this->_messages ) ) $this->_messages = array();
        $this->_messages[] = $str;
    }

    /**
     * Display any set error message in the admin console.
     */
    function DisplayErrors()
    {
        if( is_array($this->_errormsg) && count($this->_errormsg) ) {
            echo $this->ShowErrors($this->_errormsg);
            $this->_errormsg = array();
        }
    }


    /**
     * Display any set informational messages in the admin console.
     */
    function DisplayMessages()
    {
        if( is_array($this->_messages) && count($this->_messages) ) {
            $message = implode('<br/>',$this->_messages);
            echo $this->ShowMessage($message);
            $this->_messages = array();
        }
    }


    /**
     * Set the current action for the next request of the admin console.
     * Used for the various admin forms.
     *
     * @param string $action The action name
     */
    function SetCurrentAction($action)
    {
        $action = trim($action);
        $this->_current_action = $action;
    }


    /**
     * Set the current tab for the next request of the admin console.
     * Used for the various template forms.
     *
     * @param string $tab The tab name.
     */
    function SetCurrentTab($tab)
    {
        $this->_current_tab = $tab;
    }


    /**
     * A replacement for the built in SetTabHeader.
     * This method is a smart replacement that will automatically handle an active tab on the
     * requiest.
     *
     * @param string $name The tab name (for coding purposes)
     * @param string $str  The human readable label for the tab.
     * @param mixed  $state  If Empty or 'unknown' then detect any currently set tab.
     */
    function SetTabHeader($name,$str,$state = 'unknown')
    {
        if( $state == 'unknown' || $state == '') $state = ($name == $this->_current_tab);
        return parent::SetTabHeader($name,$str,$state);
    }


    /**
     * A function for using a template to display an error message.
     * This method is suitable for frontend displays.
     *
     * @deprecated
     * @param string $txt The error message
     * @param string $class An optional class attribute value.
     */
    function DisplayErrorMessage($txt,$class = 'error')
    {
        $smarty = cmsms()->GetSmarty();
        $smarty->assign('cg_errorclass',$class);
        $smarty->assign('cg_errormsg',$txt);
        $res = $this->ProcessTemplateFromDatabase('cg_errormsg','',true,'CGExtensions');
        return $res;
    }


    /**
     * A convenience function for retrieving the current error template
     *
     * @deprecated
     */
    function GetErrorTemplate()
    {
        return $this->GetTemplate('cg_errormsg','CGExtensions');
    }


    /**
     * Reset the error template to factory defaults
     *
     * @deprecated
     */
    function ResetErrorTemplate()
    {
        $fn = cms_join_path(__DIR__,'templates','orig_error_template.tpl');
        if( file_exists( $fn ) ) {
            $template = @file_get_contents($fn);
            $this->SetTemplate( 'cg_errormsg', $template,'CGExtensions' );
        }
    }


    /**
     * Set the error template
     *
     * @deprecated
     * @param string $tmpl Smarty Template source
     */
    function SetErrorTemplate($tmpl)
    {
        return $this->SetTemplate('cg_errormsg',$tmpl,'CGExtensions');
    }


    /**
     * A function to return an array of of country codes and country names.
     * i.e:  array( array('code'=>'AB','name'=>'Alberta'), array('code'=>'MB','code'=>'Manitoba'));
     * @return array
     */
    protected function get_state_list()
    {
        $db = cge_utils::get_db();
        $query = 'SELECT * FROM '.CGEXTENSIONS_TABLE_STATES.' ORDER BY sorting ASC,name ASC';
        $tmp = $db->GetAll($query);
        return $tmp;
    }


    /**
     * A function to return an array of of country codes and country names.
     * This method returns data that is suitable for use in a list.
     * i.e:  array( array('code'=>'AB','name'=>'Alberta'), array('code'=>'MB','code'=>'Manitoba'));
     *
     * @return array
     */
    protected function get_state_list_options()
    {
        $tmp = $this->get_state_list();
        $result = array();
        for( $i = 0; $i < count($tmp); $i++ ) {
            $rec =& $tmp[$i];
            $result[$rec['code']] = $rec['name'];
        }
        return $result;
    }


    /**
     * A convenience function to create a state dropdown list.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $value The initial value for the dropdown.
     * @param mixed $selectone  If true, then a hardcoded "Select One" string will be prepended to the list.  If a string then that string will be used.
     * @param string $addtext Additional text for the select tag.
     */
    function CreateInputStateDropdown($id,$name,$value='AL',$selectone=false,$addtext='')
    {
        $tmp = $this->get_state_list();

        $states = array();
        if( $selectone !== false ) {
            if( is_string($selectone) ) {
                $states[$selectone] = '';
            }
            else {
                $states[$this->Lang('select_one')] = '';
            }
        }
        foreach($tmp as $row) {
            $states[$row['name']] = $row['code'];
        }
        return $this->CreateInputDropdown($id,$name,$states,-1,strtoupper($value),$addtext);
    }


    /**
     * A function to return an array of of country codes and country names.
     * i.e:  array( array('code'=>'US','name'=>'United States'), array('code'=>'CA','code'=>'Canada'));
     */
    protected function get_country_list()
    {
        $db = cge_utils::get_db();
        $query = 'SELECT * FROM '.CGEXTENSIONS_TABLE_COUNTRIES.' ORDER BY sorting ASC,name ASC';
        $tmp = $db->GetAll($query);
        return $tmp;
    }


    /**
     * A function to return an array of of country codes and country names.
     * This method returns data suitable for giving to smarty and displaying in a dropdown.
     */
    protected function get_country_list_options()
    {
        $tmp = $this->get_country_list();
        $result = array();
        for( $i = 0; $i < count($tmp); $i++ ) {
            $rec =& $tmp[$i];
            $result[$rec['code']] = $rec['name'];
        }
        return $result;
    }


    /**
     * A convenience function to create a country dropdown list
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $value The initial value for the dropdown.
     * @param mixed $selectone  If true, then a hardcoded "Select One" string will be prepended to the list.  If a string then that string will be used.
     * @param string $addtext Additional text for the select tag.
     */
    function CreateInputCountryDropdown($id,$name,$value='US',$selectone=false,$addtext='')
    {
        $tmp = $this->get_country_list();

        if( is_array($tmp) && count($tmp) ) {
            $countries = array();
            if( $selectone !== false ) $countries[$this->Lang('select_one')] = '';
            foreach($tmp as $row) {
                $countries[$row['name']] = $row['code'];
            }
            return $this->CreateInputDropdown($id,$name,$countries,-1,strtoupper($value),$addtext);
        }
    }


    /**
     * A convenience function to get the country name given the acronym
     *
     * @param string $the_acronym
     * @return string
     */
    function GetCountry($the_acronym)
    {
        $db = cge_utils::get_db();
        $query = 'SELECT name FROM '.CGEXTENSIONS_TABLE_COUNTRIES.' WHERE code = ?';
        $name = $db->GetOne($query,array($the_acronym));
        return $name;
    }


    /**
     * A convenience function to get the state name given the acronym
     *
     * @param string $the_acronym
     * @return string
     */
    function GetState($the_acronym)
    {
        $db = cge_utils::get_db();
        $query = 'SELECT name FROM '.CGEXTENSIONS_TABLE_STATES.' WHERE code = ?';
        $name = $db->GetOne($query,array($the_acronym));
        return $name;
    }


    /**
     * A convenience function to create an image dropdown from all of the image files in a specified directory.
     * This method will not ignore thumbnails.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $selectedfile The initial value for the dropdown (an image filename)
     * @param string $dir The path (relative to the uploads path) to the directory to pull images from.  If not specified, the image uploads path will be used.
     * @param mixed  $none  If true, then 'None' will be prepended to the list of output images.  If a string it's value will be used.
     * @return string.
     */
    function CreateImageDropdown($id,$name,$selectedfile,$dir = '',$none = '')
    {
        $config = cmsms()->GetConfig();

        if( startswith( $dir, '.' ) ) $dir = '';
        if( $dir == '' ) $dir = $config['image_uploads_path'];
        if( !is_dir($dir) ) $dir = cms_join_path($config['uploads_path'],$dir);

        $extensions = $this->GetPreference('imageextensions');

        $filelist = cge_dir::get_file_list($dir,$extensions);
        if( $none ) {
            if( !is_string($none) ) {
                $cge = $this->GetModuleInstance('CGExtensions');
                $none = $cge->Lang('none');
            }
            $filelist = array_merge(array($none=>''),$filelist);
        }
        return $this->CreateInputDropdown($id,$name,$filelist,-1,$selectedfile);
    }


    /**
     * A convenience function to create a list of filenames in a specified directory.
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $selectedfile The initial value for the dropdown (an image filename)
     * @param string $dir The path (relative to the uploads path) to the directory to pull images from.  If not specified, the image uploads path will be used.
     * @param string $extensions A comma separated list of filename extensions to include in the list.  If not specified the module preference will be used.
     * @param bool   $allownone Allow no files to be selected.
     * @param bool   $allowmultiple To allow selecting multiple files.
     * @param int    $size The size of the dropdown.
     * @return string.
     */
    function CreateFileDropdown($id,$name,$selectedfile='',$dir = '',$extensions = '',$allownone = '',$allowmultiple = false,$size = 3)
    {
        $config = cmsms()->GetConfig();

        if( $dir == '' ) $dir = $config['uploads_path'];
        else {
            while( startswith($dir,'/') && $dir != '' ) $dir = substr($dir,1);
            $dir = $config['uploads_path'].$dir;
        }
        if( $extensions == '' ) $extensions = $this->GetPreference('fileextensions','');

        $tmp = cge_dir::get_file_list($dir,$extensions);
        $tmp2 = array();
        if( !empty($allownone) ) $tmp2[$this->Lang('none')] = '';
        $filelist = array_merge($tmp2,$tmp);

        if( $allowmultiple ) {
            if( !endswith($name,'[]') ) $name .= '[]';
            return $this->CreateInputSelectList($id,$name,$filelist,array(),$size);
        }
        return $this->CreateInputDropdown($id,$name,$filelist,-1,$selectedfile);
    }


    /**
     * A convenience function to create a color selection dropdown
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name the name for the dropdown.
     * @param string $selectedvalue The initial value for the input field.
     * @return string
     */
    function CreateColorDropdown($id,$name,$selectedvalue='')
    {
        $this->_load_form();
        $cgextensions = $this->GetModuleInstance('CGExtensions');
        return cge_CreateColorDropdown($cgextensions,$id,$name,$selectedvalue);
    }

    /* ======================================== */
    /* IMAGE FUNCTIONS                         */
    /* ======================================== */

    /**
     * @ignore
     */
    function TransformImage($srcSpec,$destSpec,$size='')
    {
        return cge_image::transform_image($srcSpec,$destSpec,$size);
    }

    /**
     * A convenience method to create an image tag.
     * This method will automatically search through added image dirs for frontend and admin requests
     * and through the admin theme directories for admin requests.
     *
     * @deprecated
     * @see cge_tags::create_image
     * @see AddImageDir.
     * @param string $id The module action id
     * @param string $alt The alt attribute for the tag
     * @param int $width Width in pixels
     * @param int $height Height in pixels
     * @param string $class Value for the class attribute
     * @param string $addtext Additional text for the img tag.
     * @return string
     */
    function CreateImageTag($id,$alt='',$width='',$height='',$class='', $addtext='')
    {
        $this->_load_main();
        return $this->_obj->CreateImageTag($id,$alt,$width,$height,$class,$addtext);
    }


    /**
     * A convenience method to display an image.
     * This method will automatically search through added image dirs for frontend and admin requests
     * and through the admin theme directories for admin requests.
     *
     * @deprecated
     * @see cge_tags::create_image
     * @see AddImageDir.
     * @param string $image The basename for the desired image.
     * @param string $alt The alt attribute for the tag
     * @param string $class Value for the class attribute
     * @param int $width Width in pixels
     * @param int $height Height in pixels
     * @return string
     */
    function DisplayImage($image,$alt='',$class='',$width='',$height='')
    {
        $this->_load_main();
        return $this->_obj->DisplayImage($image,$alt,$class,$width,$height);
    }


    /**
     * A convenience method to create a link to a module action containing an image and optionally some text.
     *
     * This method will automatically search through added image dirs for frontend and admin requests
     * and through the admin theme directories for admin requests.
     *
     * @deprecated
     * @see CreateLink
     * @see CreateURL())
     * @see cge_tags::create_image
     * @see AddImageDir())
     * @see DisplayImage()
     * @param string $id The module action id
     * @param string $action The name of the destination action
     * @param int $returnid The page for the destination of the request.  Empty for admin requests.
     * @param string $contents The text content of the image.
     * @param string $image The basename of the image to display.
     * @param array  $params Additional link parameters
     * @param string $classname Class for the img tag.
     * @param string $warn_message An optional confirmation message
     * @param bool   $imageonly Wether the contents (if specified) should be ignored.
     * @param bool $inline
     * @param string $addtext
     * @param bool $targetcontentonly
     * @param string $prettyurl An optional pretty url slug.
     * @return string
     */
    function CreateImageLink($id,$action,$returnid,$contents,$image, $params=array(),$classname='',
                             $warn_message='',$imageonly=true, $inline=false,
                             $addtext='',$targetcontentonly=false,$prettyurl='')
    {
        $this->_load_main();
        return $this->_obj->CreateImageLink($id,$action,$returnid,$contents,$image, $params,$classname,$warn_message,
                                            $imageonly,$inline,$addtext, $targetcontentonly,$prettyurl);
    }



    /**
     * Add a directory to the list of searchable directories
     *
     * @param string $dir A directory relative to this modules installation directory.
     */
    function AddImageDir($dir)
    {
        if( strpos('/',$dir) !== 0 ) $dir = "modules/".$this->GetName().'/'.$dir;
        $this->_image_directories[] = $dir;
    }


    /**
     * List all templates stored with this module that begin with the same prefix.
     *
     * @deprecated
     * @see cge_template_utils::get_templates_by_prefix()
     * @param string $prefix The optional prefix
     * @param bool $trim
     * @return array
     */
    function ListTemplatesWithPrefix($prefix='',$trim = false )
    {
        return cge_template_utils::get_templates_by_prefix($this,$prefix,$trim);
    }


    /**
     * Create a dropdown of all templates beginning with the specified prefix
     *
     * @deprecated
     * @param string $id The module action id
     * @param string $name The name for the input element.
     * @param string $prefix The optional prefix
     * @param string $selectedvalue The default value for the input element
     * @param string $addtext
     * @return string
     */
    function CreateTemplateDropdown($id,$name,$prefix='',$selectedvalue=-1,$addtext='')
    {
        return cge_template_utils::create_template_dropdown($id,$name,$prefix,$selectedvalue,$addtext);
    }


    /**
     * Part of the multiple database template functionality
     * this function provides an interface for adding, editing,
     * deleting and marking active all templates that match
     * a prefix.
     *
     * @deprecated Use the CmsLayoutTemplate class(es) in 2.0 capable modules.
     * @param string $id The module action id (pass in the value from doaction)
     * @param int $returnid The page id to use on subsequent forms and links.
     * @param string $prefix The template prefix
     * @param string $defaulttemplatepref The name of the template containing the system default template.  This can either be the name of a database template or a filename ending with .tpl.
     * @param string $active_tab The tab to return to
     * @param string $defaultprefname The name of the preference that contains the name of the current default template.  If empty string then there will be no possibility to set a default template for this list.
     * @param string $title Title text to display in the add/edit template form
     * @param string $info Information text to display in the add/edit template form
     * @param string $destaction The action to return to.
     */
    function ShowTemplateList($id,$returnid,$prefix, $defaulttemplatepref,$active_tab, $defaultprefname,
                              $title,$info = '',$destaction = 'defaultadmin')
    {
        $cgextensions = $this->GetModuleInstance('CGExtensions');
        return $cgextensions->_DisplayTemplateList($this,$id,$returnid,$prefix, $defaulttemplatepref,$active_tab,
                                                   $defaultprefname,$title,$info,$destaction);
    }


    /**
     * @ignore
     */
    function _DisplayTemplateList(&$module,$id,$returnid,$prefix,	$defaulttemplatepref,$active_tab,$defaultprefname,
                                  $title, $info = '',$destaction = 'defaultadmin')
    {
        $this->_load_main();
        return $this->_obj->_DisplayTemplateList($module,$id,$returnid,$prefix,$defaulttemplatepref,$active_tab,
                                                 $defaultprefname,$title,$info,$destaction);
    }



    /**
     * GetDefaultTemplateForm.
     * A function to return a form suitable for editing a single template.
     *
     * @deprecated (this functionality is irrelevant in CMSMS 2.0)
     * @see cge_template_admin::get_start_template_form
     * @param GExtensions $module A CGExtensions derived module reference
     * @param string $id
     * @param string $returnid
     * @param string $prefname
     * @param string $action
     * @param string $active_tab
     * @param string $title
     * @param string $filename
     * @param string $info
     * @return string
     */
    function GetDefaultTemplateForm(&$module,$id,$returnid,$prefname,$action,$active_tab,$title,$filename, $info = '')
    {
        return cge_template_admin::get_start_template_form($module,$id,$returnid,$prefname,$action,$active_tab,$title,
                                                           $filename,$info);
    }


    /**
     * EditDefaultTemplateForm
     *
     * A function to return a form suitable for editing a single template.
     *
     * @deprecated (this functionality is irrelevant in CMSMS 2.0)
     * @see cge_template_admin::get_start_template_form
     * @param GExtensions $module A CGExtensions derived module reference
     * @param string $id
     * @param string $returnid
     * @param string $prefname
     * @param string $active_tab
     * @param string $title
     * @param string $filename
     * @param string $info
     * @param string $action
     * @return string
     */
    function EditDefaultTemplateForm(&$module,$id,$returnid,$prefname, $active_tab,$title,$filename,$info = '',$action = 'defaultadmin')
    {
        echo cge_template_admin::get_start_template_form($module,$id,$returnid,$prefname, $action,$active_tab,$title, $filename,$info);
    }


    /**
     * A convenience function to create a url to a certain CMS page
     *
     * @param mixed $pageid A frontend page id or alias.
     * @return string
     */
    function CreateContentURL($pageid)
    {
        die('this is still used');
        $config = cmsms()->GetConfig();

        $contentops = cmsms()->GetContentOperations();
        $alias = $contentops->GetPageAliasFromID( $pageid );

        $text = '';
        if ($config["assume_mod_rewrite"]) {
            // mod_rewrite
            if( $alias == false ) {
                return '<!-- ERROR: could not get an alias for pageid='.$pageid.'-->';
            }
            else {
                $text .= $config["root_url"]."/".$alias.(isset($config['page_extension'])?$config['page_extension']:'.shtml');
            }
        }
        else {
            $text .= $config["root_url"]."/index.php?".$config["query_var"]."=".$pageid;
            return $text;
        }
    }


    /**
     * Get the username of the currently logged in admin user.
     *
     * @deprecated
     * @param int $uid
     * @return string
     */
    function GetAdminUsername($uid)
    {
        $user = UserOperations::LoadUserByID($uid);
        return $user->username;
    }


    /**
     * Get a human readable error message for an upload code.
     *
     * @deprecated
     * @see cg_fileupload
     * @param string $code The upload error code.
     * @return string
     */
    function GetUploadErrorMessage($code)
    {
        $cgextensions = $this->GetModuleInstance('CGExtensions');
        return $cgextensions->Lang($code);
    }


    /**
     * @ignore
     */
    function is_alias($str)
    {
        if( !preg_match('/^[\-\_\w]+$/', $str) ) return false;
        return true;
    }


    /**
     * Set the actionid for redirect methods.
     *
     * @deprecated
     * @internal
     * @param int $id
     */
    function set_action_id($id)
    {
        $this->_actionid = $id;
    }


    /**
     * Return the current action id
     *
     * @depcreated
     * @internal
     * @return int
     */
    function get_action_id()
    {
        return $this->_actionid;
    }


    /**
     * Return the current action id
     *
     * @depcreated
     * @internal
     * @return int
     */
    function GetActionId()
    {
        if( !method_exists($this,'get_action_id') && $this->GetName() != 'CGExtensions' ) {
            die('FATAL ERROR: A module derived from CGExtensions is not handling the get_action_id method');
        }
        return $this->get_action_id();
    }


    /**
     * Get a form for adding or editing a single template.
     *
     * @deprecated
     * @see cge_template_admin::get_single_template_form
     * @param CGExtensions $module A CGExtensions module reference
     * @param string $id
     * @param int $returnid
     * @param string $tmplname The name of the template to edit
     * @param string $active_tab
     * @param string $title
     * @param string $filename The name of the file (in the module's template directory) containing the system default template.
     * @param string $info
     * @param string $destaction
     */
    function GetSingleTemplateForm(&$module,$id,$returnid,$tmplname,$active_tab,$title,$filename, $info = '',$destaction='defaultadmin')
    {
        return cge_template_admin::get_single_template_form($module,$id,$returnid,$tmplname,$active_tab,$title,$filename,
                                                            $info,$destaction);
    }


    /**
     * Retrieve a human readable string for any error generated during watermarking.
     *
     * @deprecated
     * @param string $error the watermarking error code
     * @return string
     */
    function GetWatermarkError($error)
    {
        if( empty($error) || $error === 0 ) return '';
        $mod = $this->GetModuleInstance('CGExtensions');
        return $mod->Lang('watermarkerror_'.$error);
    }


    /**
     * Setup and initializing charting functionality
     *
     * @deprecated
     */
    function InitializeCharting()
    {
        require_once(__DIR__.'/lib/pData.class');
        require_once(__DIR__.'/lib/pChart.class');
    }


    /**
     * Initialize associative data functionality
     *
     * @deprecated
     */
    function InitializeAssocData()
    {
        require_once(__DIR__.'/lib/class.AssocData.php');
    }


    /**
     * A convenience method to clear any session data associated with this module.
     *
     * @param string $key If not specified clear all session data relative to this module."
     */
    function session_clear($key = '')
    {
        if( empty($key) ) {
            unset($_SESSION[$this->GetName()]);
        }
        else {
            unset($_SESSION[$this->GetName()][$key]);
        }
    }

    /**
     * A convenience method to store some session data associated with this module.
     *
     * @param string $key The variable key.
     * @param string $value The data to store.
     */
    function session_put($key,$value)
    {
        if( !isset($_SESSION[$this->GetName()]) ) $_SESSION[$this->GetName()] = array();
        $_SESSION[$this->GetName()][$key] = $value;
    }

    /**
     * A convenience method to retrieve some session data associated with this module.
     *
     * @param string $key The variable key.
     * @param string $dfltvalue "The default value to return if the specified data does not exist."
     * @return mixed.
     */
    function session_get($key,$dfltvalue='')
    {
        if( !isset($_SESSION[$this->GetName()]) ) return $dfltvalue;
        if( !isset($_SESSION[$this->GetName()][$key]) ) return $dfltvalue;
        return $_SESSION[$this->GetName()][$key];
    }


    /**
     * Return data identified by a key either from the supplied parameters, or from session.
     *
     * @param array $params Input parameters
     * @param string $key The data key
     * @param string $defaultvalue  The data to return if the specified data does not exist in the session or in the input parameters.
     * @return mixed.
     */
    function param_session_get(&$params,$key,$defaultvalue='')
    {
        if( isset($params[$key]) ) return $params[$key];
        return $this->session_get($key,$defaultvalue);
    }


    /**
     * Given a page alias resolve it to a page id.
     *
     * @param mixed $txt The page alias to resolve.  If an integer page id is passed in that is acceptable as well.
     * @param int $dflt The default page id to return if no match can be found
     * @return int
     */
    function resolve_alias_or_id($txt,$dflt = null)
    {
        $txt = trim($txt);
        if( $txt ) {
            $manager = cmsms()->GetHierarchyManager();
            $node = $manager->find_by_tag('alias',$txt);
            if( !isset($node) ) $node = $manager->find_by_tag('id',(int)$txt);
            if( is_object($node) ) return (int)$node->get_tag('id');
        }
        return $dflt;
    }


    /**
     * Perform an HTTP post request.
     *
     * @param string $URL the url to post to
     * @param array $data The array to post.
     * @param string $referer An optional referrer string.
     * @return string
     */
    function http_post($URL,$data = '',$referer='')
    {
        return cge_http::post($URL,$data,$referer);
    }


    /**
     * Perform an HTTP GET request.
     *
     * @param string $URL the url to post to
     * @param string $referer An optional referrer string.
     * @return string
     */
    function http_get($URL,$referer='')
    {
        return cge_http::get($URL,$referer);
    }

  /**
   * Similar to GetPreference except the default value is used even if the preference exists, but is blank.
   *
   * @param string $pref_name The preference name
   * @param string $dflt_value The default value for the preference if not set (or empty)
   * @param bool $allow_empty Wether the default value should be used if the preference exists, but is empty.
   * @return string.
   */
  public function CGGetPreference($pref_name,$dflt_value = null,$allow_empty = FALSE)
  {
    $tmp = trim($this->GetPreference($pref_name,$dflt_value));
    if( !empty($tmp) || is_numeric($tmp) ) return $tmp;
    if( $allow_empty ) return $tmp;
    return $dflt_value;
  }

  /**
   * A wrapper to get a module specific user preference.
   * this method only applies to admin users.
   *
   * @param string $pref_name The preference name
   * @param string $dflt_value The default value for the preference if not set (or empty)
   * @param bool $allow_empty Wether the default value should be used if the preference exists, but is empty.
   * @return string.
   */
  public function CGGetUserPreference($pref_name,$dflt_value = null,$allow_empty = FALSE)
  {
      $key = '__'.$this->GetName().'_'.$pref_name;
      $tmp = cms_userprefs::get($key,$dflt_value);
      if( !empty($tmp) || is_numeric($tmp) ) return $tmp;
      if( $allow_empty ) return $tmp;
      return $dflt_value;
  }

  /**
   * A wrapper to set a user preference that is module specific.
   * this method only applies to admin users.
   *
   * @param string $pref_name The preference name
   * @param string $value The preference value.
   */
  public function CGSetUserPreference($pref_name,$value)
  {
      $key = '__'.$this->GetName().'_'.$pref_name;
      return cms_userprefs::set($key,$value);
  }

  /**
   * A wrapper to remove a user preference that is module specific.
   * this method only applies to admin users.
   *
   * @param string $pref_name The preference name
   * @param string $value The preference value.
   */
  public function CGRemoveUserPreference($pref_name)
  {
      $key = '__'.$this->GetName().'_'.$pref_name;
      return cms_userprefs::remove($key,$value);
  }

  /**
   * find a file for this module
   * looks in module_custom, and in the module directory
   *
   * @param string $filename
   * @return string
   */
  public function find_file($filename)
  {
      if( !$filename ) return;
      $config = cmsms()->GetConfig();
      $dirlist = array();
      $dirlist[] = $config['root_path']."/module_custom/".$this->GetName();
      $dirlist[] = $config['root_path']."/module_custom/".$this->GetName()."/templates";
      $dirlist[] = $this->GetModulePath();
      $dirlist[] = $this->GetModulePath()."/templates";
      foreach( $dirlist as $dir ) {
          $fn = "$dir/$filename";
          if( file_exists($fn) ) return $fn;
      }
  }

  /**
   * A convenience method to generate a smarty resource string given a template name and an optional prefix.
   * if the supplied template name ends with .tpl then a file template is assumed.
   *
   * @param string $template_name The desired template name
   * @param string $prefix an optional prefix for database templates.
   * @return string
   */
  public function CGGetTemplateResource($template_name,$prefix = null)
  {
      $template_name = trim($template_name);
      if( endswith($template_name,'.tpl') ) return $this->GetFileResource($template_name);
      return $this->GetDatabaseResource($prefix.$template_name);
  }

  /**
   * An advanced method to process either a file, or database template for this module
   * through smarty
   *
   * @param string $template_name  The template name.  If the value of this parameter ends with .tpl then a file template is assumed.  Otherwise a database template is assumed.
   * @param string $prefix  For database templates, optionally prefix thie template name with this value.
   * @return string The output from the processed smarty template.
   */
  public function CGProcessTemplate($template_name,$prefix = null)
  {
      $template_name = trim($template_name);
      if( endswith($template_name,'.tpl') ) return $this->ProcessTemplate($template_name);
      return $this->ProcessTemplateFromDatabase($prefix.$template_name);
  }
} // class

// EOF
?>
