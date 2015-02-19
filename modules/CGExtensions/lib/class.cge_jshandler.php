<?php

final class cge_jshandler
{
    private function __construct() {}

    public static function load($libname)
    {
        $mod = cms_utils::get_module('CGExtensions');
        if( $libname == 'cg_cmsms' ) {
            // gotta return code.
            $config = cmsms()->GetConfig();
            $smarty = cmsms()->GetSmarty();
            $smarty->assign('mod',$mod);
            $smarty->assign('config',$config);
            $code = $mod->ProcessTemplate('jquery.cg_cmsms.tpl');
            $obj = new StdClass;
            $obj->code = $code;
            return $obj;
        }
    }
} // end of class

?>