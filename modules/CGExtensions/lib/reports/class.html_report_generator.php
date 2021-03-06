<?php

/**
 * This file defines the abstract tabular report generator class.
 *
 * @package CGExtensions
 * @category Reports
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

namespace CGExtensions\reports;

/**
 * An abstract class to provide the basics for outputting an HTML report.
 */
abstract class html_report_generator extends tabular_report_generator
{
    /**
     * @ignore
     */
    private $_in_table;

    /**
     * @ignore
     */
    private $_status;

    /**
     * @ignore
     */
    private $_out;

    /**
     * @ignore
     */
    private $_alias;

    /**
     * @ignore
     */
    private $_curgroup;

    /**
     * @ignore
     */
    private $_stylesheets = array();

    /**
     * @ignore
     */
    private $_idx;

    /**
     * An abstract function that is called to output the head (and beginning body tag) of the HTML report.
     *
     * @abstract
     * @return string
     */
    protected function do_head()
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
        $out .= "</head>\n";

        $out .= '<body id="'.$this->get_alias().'">';
        return $out;
    }

    /**
     * @ignore
     */
    protected function start()
    {
        $out = $this->do_head();
        $title = $this->report()->get_title();
        if( $title ) $out .= '<h1>'.htmlentities($title,ENT_QUOTES).'</h1>';
        $desc = $this->report()->get_description();
        if( $desc ) $out .= '<p class="description">'.htmlentities($desc,ENT_QUOTES).'</p>';
        $out .= '<table id="report_data">';
        $this->_in_table = TRUE;
        $this->_out .= $out;
        parent::start();
    }

    /**
     * Geerate tags (such as stylesheet tags) that are required for the HEAD portion of the HTML output.
     *
     * @return string The HTML tags to go into the HEAD portion.
     */
    protected function get_head_contents()
    {
        $out = '';
        if( count($this->_stylesheets) ) {
            $mod = \cge_utils::get_cge();
            foreach( $this->_stylesheets as $one ) {
                if( !file_exists($one) ) continue;
                $url = \cge_utils::file_to_url($one);
                if( !$url ) continue;
                $out .= '<link rel="stylesheet" type="text/css" href="'.$url.'">';
            }
        }
        return $out;
    }

    /**
     * @ignore
     */
    protected function before_line()
    {
        parent::before_line();
        if( $this->_in_table ) {
            // start a row
            $classes = array();
            $tag = 'tr';

            switch( $this->_status ) {
            case 'RPTHEADER':
                $classes[] = 'rptheader';
                $this->_idx++;
                $classes[] = 'rptheader'.$this->_idx;
                $this->_out .= '<tr class="'.implode(' ',$classes).'">';
                break;

            case 'HEADER':
                $classes[] = 'header';
                $this->_idx++;
                if( $this->_curgroup ) $classes[] = 'hdr-'.munge_string_to_url($this->_curgroup->get_column()).$this->_idx;
                $this->_out .= '<tr class="'.implode(' ',$classes).'">';
                break;

            case 'FOOTER':
                $classes[] = 'footer';
                $this->_idx++;
                if( $this->_curgroup ) $classes[] = 'ftr-'.munge_string_to_url($this->_curgroup->get_column()).$this->_idx;
                $this->_out .= '<tr class="'.implode(' ',$classes).'">';
                break;

            case 'RPTFOOTER':
                $classes[] = 'rptfooter';
                $this->_idx++;
                $classes[] = 'rptfooter'.$this->_idx;
                $this->_out .= '<tr class="'.implode(' ',$classes).'">';
                break;

            default:
                $this->_out .= '<tr>';
                break;
            }
        }
    }

    /**
     * @ignore
     */
    protected function after_line()
    {
        parent::after_line();
        if( $this->_in_table ) {
            // end a row
            $this->_out .= "</tr>\n";
        }
    }

    /**
     * @ignore
     */
    protected function before_group_headers()
    {
        $this->_status = 'HEADER';
    }

    /**
     * @ignore
     */
    protected function after_group_headers()
    {
        $this->_status = null;
    }

    /**
     * @ignore
     */
    protected function before_group_footers()
    {
        $this->_status = 'FOOTER';
        //this->_out .= $out;
    }

    /**
     * @ignore
     */
    protected function after_group_footers()
    {
        $this->_status = null;
        $this->_curgroup = null;
    }

    /**
     * @ignore
     */
    protected function before_group_header(tabular_report_defn_group $grp)
    {
        if( $grp->is_report_group() ) $this->_status = 'RPTHEADER';
        $this->_idx = 0;
        $this->_curgroup = $grp;

        if( $grp->is_report_group() || $this->_record_number == 0 ) return; // after and before actions are ignored for report groups.
        if( ($act = $grp->get_before_action() ) ) {
            switch( $act ) {
            case $grp::ACT_PAGE:
                $this->_out .= '<tr class="pagebreak" style="display: block; page-break-after: always;"><td></td></tr>';
            default:
                // do nothing
            }
        }
    }

    /**
     * @ignore
     */
    protected function after_group_header(tabular_report_defn_group $grp)
    {
        if( $grp->is_report_group() ) $this->_status = NULL;
        $this->_curgroup = null;
    }

    /**
     * @ignore
     */
    protected function before_group_footer(tabular_report_defn_group $grp)
    {
        if( $grp->is_report_group() ) {
            $this->_status = 'RPTFOOTER';
        }
        $this->_idx = 0;
        $this->_curgroup = $grp;
    }

    /**
     * @ignore
     */
    protected function after_group_footer(tabular_report_defn_group $grp)
    {
        $this->_curgroup = null;
        if( $grp->is_report_group() ) return; // after and before actions are ignored for report groups.
        if( ($act = $grp->get_after_action() ) ) {
            switch( $act ) {
            case $grp::ACT_PAGE:
                 $this->_out .= '<tr class="pagebreak" style="display: block; page-break-after: always;"><td></td></tr>';
            default:
                // do nothing
            }
        }
    }

    /**
     * @ignore
     */
    protected function draw_cell(tabular_report_cellfmt $col,$val)
    {
        $attrs = array();
        $attrs['class'] = array($col->get_key());
        if( ($class = $col->get_class()) ) $attrs['class'][] = $class;
        if( ($aval = $col->get_alignment()) ) $attrs['style'][] = "text-align: $aval";
        if( $col->get_span() > 1 ) $attrs['colspan'] = $col->get_span();

        if( $this->_status == 'HEADER' || $this->_status == 'RPTHEADER' ) {
            $el = 'th';
        }
        else {
            $el = 'td';
        }

        if( isset($attrs['class']) && count($attrs['class']) ) $attrs['class'] = implode(' ',$attrs['class']);
        if( isset($attrs['style']) && count($attrs['style']) ) $attrs['style'] = implode('; ',$attrs['style']);
        $out = null;
        foreach( $attrs as $akey => $aval ) {
            $out .= " $akey=\"{$aval}\"";
        }

        $this->_out .= "<{$el}{$out}><span>{$val}</span></{$el}>";
    }

    /**
     * @ignore
     */
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

    /**
     * @ignore
     */
    public function get_output()
    {
        return $this->_out;
    }

    /**
     * Set an alias for this report.
     * The alias can be used for locating stylesheets or other files unique to this report.
     *
     * @param string $str
     * @return void
     */
    public function set_alias($str)
    {
        $str = trim((string) $str);
        $this->_alias = $str;
    }

    /**
     * Get this object's alias
     * If not explicitly specified, an alias will be automatically generated.
     *
     * @return string
     */
    public function get_alias()
    {
        $alias = $this->_alias;
        if( !$alias ) {
            $alias = $this->report()->get_title();
            $alias = munge_string_to_url($alias);
            $this->set_alias($alias);
        }
        return $alias;
    }

    /**
     * Add a stylesheet to the output.
     * Multiple stylesheets are permitted.
     * By default, the get_head_contents() method will read this list and generate stylesheet links.
     *
     * @param string $filename The complete filename to the css file. Must be relative to the website root url or uploads url.
     */
    public function add_stylesheet($filename)
    {
        $filename = trim($filename);
        if( !$filename ) return;
        if( !in_array($filename,$this->_stylesheets) ) $this->_stylesheets[] = $filename;
    }
} // end of class

?>