<?php

namespace CGExtensions;

class lookup_table_manager
{
    private $_data = array('_m'=>null,'_c'=>null);

    public function __construct($module,$class)
    {
        $this->_data['_m'] = (string) $module;
        $this->_data['_c'] = (string) $class;

        // @todo: check to see if module works
    }

    public function get_class()
    {
        return $this->_data['class'];
    }

    public function display_manager()
    {
        // only for admin requests.
        if( cmsms()->is_frontend_request() ) throw new \LogicException(__METHOD__.' cannot be used for frontend requests.');

        $class = $this->_data['_c'];
        $list = $class::load_all();

        // transform the list into a simple class
        $mod = \cms_utils::get_module(MOD_CGEXTENSIONS);
        $newlist = array();
        $keys = array_keys($list);
        for( $i = 0; $i < count($keys); $i++ ) {
            $key = $keys[$i];
            $item = $list[$key];
            $n_item = new \StdClass;
            $n_item->id = $item->id;
            $n_item->name = $item->name;
            $n_item->description = $item->description;
            $n_item->iorder = $item->iorder;

            $parms = $this->_data;
            $parms['_i'] = $n_item->id;
            $n_item->edit_url = $mod->create_url('m1_','admin_lkp_edititem','',\cge_utils::encrypt_params($parms));
            $n_item->del_url = $mod->create_url('m1_','admin_lkp_delitem','',\cge_utils::encrypt_params($parms));
            if( $i > 0 && count($list) > 1 ) {
                // can move up
                $parms['_dir'] = 'up';
                $n_item->up_url = $mod->create_url('m1_','admin_lkp_moveitem','',\cge_utils::encrypt_params($parms));
            }
            if( count($list) > 1 && $i < count($list) - 1 ) {
                // can move down
                $parms['_dir'] = 'down';
                $n_item->down_url = $mod->create_url('m1_','admin_lkp_moveitem','',\cge_utils::encrypt_params($parms));
            }

            $newlist[] = $n_item;
        }
        $tpl = $mod->CreateSmartyTemplate('lookup_table_list.tpl');
        $tpl->assign('items',$newlist);
        $tpl->assign('class',$class);
        $tpl->assign('cge',$mod);
        $tpl->assign('add_url',$mod->create_url('m1_','admin_lkp_edititem','',\cge_utils::encrypt_params($this->_data)));
        $tpl->display();
    }
} // end of class

?>