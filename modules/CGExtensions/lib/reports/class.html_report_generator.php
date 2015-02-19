<?php

namespace CGExtensions\reports;

// this is a simple, hardcoded html report
// does not do pagination.
class html_report_generator extends tabular_report_generator
{
    private $_in_table;
    private $_status;
    private $_out;

    protected function start()
    {
        $out = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $out .= '<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-gb" xml:lang="en-gb">';
        $out .= '<head>';
        $out .= '<meta http-equiv="content-type" content="text/html; charset=UTF-8" />';
        $tmp = $this->get_head_contents();
        if( $tmp ) $out .= $tmp."\n";
        $title = $this->report()->get_title();
        if( $title ) $out .= '<title>'.htmlentities($title,ENT_QUOTES).'</title>';
        $desc = $this->report()->get_description();
        if( $desc ) $out .= sprintf('<meta name="description" content="%s"/>',htmlentities($desc,ENT_QUOTES));
        $out .= "</head>\n<body>\n";
        if( $title ) $out .= '<h1>'.htmlentities($title,ENT_QUOTES).'</h1>';
        if( $desc ) $out .= '<p class="description">'.htmlentities($desc,ENT_QUOTES).'</p>';
        $out .= '<table align="center" width="100%">';
        $this->_in_table = TRUE;
        $this->_out .= $out;
    }

    protected function get_head_contents() {}

    protected function before_line()
    {
        parent::before_line();
        if( $this->_in_table ) {
            // start a row
            switch( $this->_status ) {
            case 'HEADER':
                $str = "header";
                if( $this->_idx > 0 ) $str .= " grpheader{$this->_idx}";
                $this->_out .= '<tr class="'.$str.'">';
                break;
            case 'FOOTER':
                $str = "footer";
                if( $this->_idx > 0 ) $str .= " grpfooter{$this->_idx}";
                $this->_out .= '<tr class="'.$str.'">';
                break;
            default:
                $this->_out .= '<tr>';
                break;
            }
        }
    }

    protected function after_line()
    {
        parent::after_line();
        if( $this->_in_table ) {
            // end a row
            $this->_out .= "</tr>\n";
        }
    }

    protected function do_group_header(tabular_report_defn_group $grp,$idx)
    {
        $this->_idx = $idx+1;
        return parent::do_group_header($grp,$idx);
    }

    protected function do_group_footer(tabular_report_defn_group $grp,$idx)
    {
        $this->_idx = $idx;
        return parent::do_group_footer($grp,$idx);
    }

    protected function before_group_headers()
    {
        $this->_idx = null;
        $this->_status = 'HEADER';
    }

    protected function after_group_headers()
    {
        $this->_status = null;
        $this->_idx = null;
    }

    protected function before_group_footers()
    {
        $this->_idx = null;
        $this->_status = 'FOOTER';
        //this->_out .= $out;
    }

    protected function after_group_footers()
    {
        $this->_status = null;
        $this->_idx = null;
    }

    protected function draw_cell(tabular_report_cellfmt $col,$val)
    {
        $attrs = array();
        $attrs['class'] = $col->get_key();
        if( ($aval = $col->get_alignment()) ) $attrs['style'][] = "text-align: $aval";
        if( $col->get_span() > 1 ) $attrs['colspan'] = $col->get_span();

        if( $this->_status == 'HEADER' ) {
            $el = 'th';
        }
        else {
            $el = 'td';
        }

        if( isset($attrs['style']) && count($attrs['style']) ) $attrs['style'] = implode('; ',$attrs['style']);
        $out = null;
        foreach( $attrs as $akey => $aval ) {
            $out .= " $akey=\"{$aval}\"";
        }
        $this->_out .= "<{$el}{$out}>{$val}</{$el}>";
    }

    protected function finish()
    {
        parent::finish();
        // close off the body and html tags
        $out = '</table>';
        $out .= '<!-- generated on '.strftime('%x %H:%M').' -->';
        $out .= '</body></html>'."\n";
        $this->_out .= $out;
        $this->_in_table = FALSE;
    }

    public function get_output()
    {
        return $this->_out;
    }
} // end of class

?>