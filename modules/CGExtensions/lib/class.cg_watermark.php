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
 * This file defines the cg_watermark class.
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

/**
 * A class to provide a convenient way of watermarking images.
 * it uses preferences from the CGExtensions module admin panel.
 *
 * @package CGExtensions
 */
class cg_watermark
{
    const ALIGN_UL = 0;
    const ALIGN_UC = 1;
    const ALIGN_UR = 2;
    const ALIGN_ML = 3;
    const ALIGN_MC = 4;
    const ALIGN_MR = 5;
    const ALIGN_LL = 6;
    const ALIGN_LC = 7;
    const ALIGN_LR = 8;
    const ERROR_NOTREADY    = 1000;
    const ERROR_BADFILE     = 1001;
    const ERROR_BADFILETYPE = 1002;
    const ERROR_NOFILE      = 1003;
    const ERROR_CREATEWM    = 1004;
    const ERROR_LOADIMG     = 1005;
    const ERROR_OTHER       = 1006;

    /**
     * @ignore
     */
    private $_text;

    /**
     * @ignore
     */
    private $_bg_color;

    /**
     * @ignore
     */
    private $_text_color;

    /**
     * @ignore
     */
    private $_text_angle;

    /**
     * @ignore
     */
    private $_text_font;

    /**
     * @ignore
     */
    private $_text_size;

    /**
     * @ignore
     */
    private $_wmimg_file;

    /**
     * @ignore
     */
    private $_alignment;

    /**
     * @ignore
     */
    private $_hmargin;

    /**
     * @ignore
     */
    private $_vmargin;

    /**
     * @ignore
     */
    private $_padding_x;

    /**
     * @ignore
     */
    private $_padding_y;

    /**
     * @ignore
     */
    private $_transparent;

    /**
     * @ignore
     */
    private $_translucency;

    /**
     * @ignore
     */
    private $h_wmimage;

    /**
     * @ignore
     */
    private $h_resultimage;

    /**
     * @ignore
     */
    private $t_wmsize;

    /**
     * @ignore
     */
    private $h_textcolor;

    /**
     * @ignore
     */
    private $t_error;

    /**
     * Construct a new cg_watermark object.
     */
    public function __construct()
    {
        $this->h_wmimage = '';
        $this->h_resultimage = '';
        $this->h_textcolor = '';
        $this->h_bgcolor = '';
        $this->t_wmsize = '';
        $this->t_error = 0;

        $this->_transparent = 1;
        $this->_hmargin = 20;
        $this->_vmargin = 20;
        $this->_padding_x = 5;
        $this->_padding_y = 5;
        $this->_text = null;
        $this->_bg_color = array(0,0,0); // black
        $this->_text_color= array(255,255,255); // white
        $this->_text_angle = 0;
        $this->_text_font='';
        $this->_text_size='';
        $this->_wmimg_file = '';
        $this->_alignment = self::ALIGN_MC;
        $this->_translucency = 100;
    }

    /**
     * Return any error from the last watermark operation.
     *
     * @return string
     */
    public function get_error()
    {
        return $this->t_error;
    }

    /**
     * Set the text to use for watermarking.
     * This will ensure that no watermarking image is used.
     *
     * Note: HTML tags cannot be used.  It is unsure how newline or tab characters
     * will be rendered.
     *
     * @param string $text
     */
    public function set_watermark_text($text)
    {
        $this->_text = (string) $text;
        $this->_wmimg_file = '';
    }

    /**
     * Set the watermark image.
     * This will reset any watermark text.
     *
     * @param string $filename
     */
    public function set_watermark_image($filename)
    {
        $filename = (string) $filename;
        if( file_exists($filename) ) {
            $this->_wmimg_file = $filename;
            $this->_text = null;
        }
    }

    /**
     * Set the alignment for the watermark text or image.
     *
     * @param string $alignment One of the alignment constants.
     */
    public function set_alignment($alignment)
    {
        $this->_alignment = (string) $alignment;
    }

    /**
     * Get the alignment (if any) for this watermarking.
     *
     * The default value is ALIGN_MC
     */
    public function get_alignment()
    {
        return $this->_alignment;
    }

    /**
     * Set the font for text based watermarks.
     *
     * @param string $font
     */
    public function set_font($font)
    {
        $this->_text_font = (string) $font;
    }

    /**
     * Set the text size for text based watermarking.
     *
     * @param int $points The point size of the text.
     */
    public function set_text_size($points)
    {
        $this->_text_size = (int) $points;
    }

    /**
     * Set the angle for the text in text based watermarking.
     *
     * @param int $angle The angle (in degrees).  Default value is 0
     */
    public function set_text_angle($angle)
    {
        $angle = (int)$angle;
        $angle = $angle % 360;
        $this->_text_angle = $angle;
    }

    /**
     * Set the color for text based watermarking.
     * The default value for this setting is white (255,255,255)
     *
     * @param int $red (red portion, between 0 and 255)
     * @param int $green (green portion, between 0 and 255)
     * @param int $blue (blue portion, between 0 and 255)
     */
    public function set_text_color($red,$green,$blue)
    {
        $red = (int)$red;
        $red = max($red,0);
        $red = min($red,255);
        $green = (int)$green;
        $green = max($green,0);
        $green = min($green,255);
        $blue = (int)$blue;
        $blue = max($blue,0);
        $blue = min($blue,255);
        $this->_text_color = array($red,$green,$blue);
    }

    /**
     * Set the background color for text based watermarks.
     * The default value is  black (0,0,0) and transparent.
     *
     * @param int $red The red value for the color (between 0 and 255)
     * @param int $green The green value for the color (between 0 and 255)
     * @param int $blue The blue value for the color (between 0 and 255)
     * @param bool $transparent Wether the background color is to be treated as transparent.
     */
    public function set_background_color($red,$green,$blue,$transparent = false)
    {
        $red = (int)$red;
        $red = max($red,0);
        $red = min($red,255);
        $green = (int)$green;
        $green = max($green,0);
        $green = min($green,255);
        $blue = (int)$blue;
        $blue = max($blue,0);
        $blue = min($blue,255);
        $this->_bg_color = array($red,$green,$blue);
        $this->_transparent = (bool) $transparent;
    }

    /**
     * Allows setting the alpha channel translucency for watermarks.
     *
     * @param int $num A value between 0 and 100 where 100 is absolutely no translucency.
     */
    public function set_translucency($num)
    {
        $num = (int)$num;
        $num = max($num,0);
        $num = min($num,100);
        $this->_translucency = $num;
    }

    /**
     * Test wether all information is set and ready for performing watermarking on an image.
     *
     * @return bool
     */
    public function is_ready()
    {
        if( empty($this->_wmimg_file) && empty($this->_text) ) {
            $this->t_error = self::ERROR_NOTREADY;
            return FALSE;
        }

        if( !empty($this->_text) && (empty($this->_text_font) || empty($this->_text_size)) ) {
            $this->t_error = self::ERROR_NOTREADY;
            return FALSE;
        }

        if ( !empty($this->_text) && !file_exists($this->_text_font) ) {
            $this->t_error = self::ERROR_NOTREADY;
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @ignore
     */
    private function _cleanup()
    {
        // todo.
    }

    /**
     * @ignore
     */
    private function _generateImageFromText(&$width,&$height)
    {
        if( FALSE === $this->is_ready() ) return FALSE;
        if( !empty($this->h_wmimage) ) {
            // Already have The image
            return TRUE;
        }

        //
        // Generate a transparent PNG image type thing
        // with the text we want
        //

        // First find the bounding box
        $this->t_wmsize = imageftbbox($this->_text_size, $this->_text_angle,
                                      $this->_text_font, $this->_text);

        $width = abs($this->t_wmsize[0])+abs($this->t_wmsize[2]);
        $height = abs($this->t_wmsize[1])+abs($this->t_wmsize[5]);

        $image = imagecreatetruecolor($width+$this->_hmargin, $height+$this->_vmargin);

        $this->h_bgcolor = imagecolorallocate($image, $this->_bg_color[0], $this->_bg_color[1], $this->_bg_color[2]);

        // background
        imagefilledrectangle($image,0,0,$width-1+$this->_hmargin,$height-1+$this->_vmargin,$this->h_bgcolor);

        if( $this->_transparent ) {
            // make the background transparent.
            imagecolortransparent($image,$this->h_bgcolor);
        }


        // draw the forgeround text
        $this->h_textcolor = imagecolorallocate($image, $this->_text_color[0], $this->_text_color[1], $this->_text_color[2]);
        $res = imageTTFText($image, $this->_text_size, $this->_text_angle, (int)($this->_hmargin/2)+1, $height+(int)($this->_vmargin/2),
                            $this->h_textcolor, $this->_text_font, $this->_text);

        $width += $this->_hmargin;
        $height += $this->_vmargin;

        // should have a nice image now.
        return $image;
    }

    /**
     * @ignore
     */
    private function _loadFile($filename,&$sizeinfo,$istransparent = false)
    {
        $tmp = getimagesize($filename);
        if( $tmp === FALSE ) {
            $this->t_error = self::ERROR_BADFILE;
            return FALSE;
        }

        $image = '';
        switch($tmp[2]) {
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($filename);
            break;
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($filename);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($filename);
            break;
        default:
            $this->t_error = self::ERROR_BADFILETYPE;
            return FALSE;
        }

        if( $istransparent ) {
            $c = imagecolorat($image,1,1);
            imagecolortransparent($image,$c);
        }
        $sizeinfo = $tmp;
        return $image;
    }

    /**
     * Watermark an input image given the current settings, and
     * output it into the destination location.
     *
     * @param string $srcfile The input file specification.  Must be an image file
     * @param string $destfile The output file specification.  Must be a writable location.
     * @return bool
     */
    public function create_watermarked_image($srcfile,$destfile)
    {
        if( empty($srcfile) || empty($destfile) ) return FALSE;

        if( !file_exists($srcfile) )	{
            $this->t_error = self::ERROR_NOFILE;
            return FALSE;
        }

        // check if we're ready
        if( FALSE === $this->is_ready() ) return FALSE;

        // load or create our watermark image
        $res = FALSE;
        $wminfo = '';
        $srcinfo = '';
        if( !empty($this->_text) ) {
            // generate text watermark image
            // dynamically
            $width = '';
            $height = '';
            $res = $this->_generateImageFromText($width,$height);
        }
        if( $res !== FALSE ) {
            $wminfo = array($width,$height,IMAGETYPE_PNG);
        }
        else {
            // load image from file
            $res = $this->_loadFile($this->_wmimg_file,$wminfo,true);
        }
        if( FALSE === $res ) {
            if( $this->t_error == 0 ) $this->t_error = self::ERROR_CREATEWM;
            $this->_cleanup();
            return FALSE;
        }
        $this->h_wmimage = $res;

        // should be able to now load the primary image
        $res = $this->_loadFile($srcfile,$srcinfo);
        if( FALSE === $res ) {
            if( $this->t_error == 0 ) $this->t_error = self::ERROR_LOADIMG;
            $this->_cleanup();
            return FALSE;
        }
        $this->h_resultimg = $res;

        // Check to make sure that the source
        // Image isn't smaller than our watermark image
        if( ($srcinfo[0] < $wminfo[0]) || ($srcinfo[1] < $wminfo[1]) ) {
            $this->t_error = self::ERROR_BADFILE;
            $this->_cleanup();
            return FALSE;
        }

        // Find out the placement of the watermark
        // on the result image
        $posx = '';
        $posy = '';
        $cx = ($srcinfo[0] - $wminfo[0])/2;
        $cy = ($srcinfo[1] - $wminfo[1])/2;
        switch( $this->_alignment ) {
        case self::ALIGN_UL:
            $posx = $this->_padding_x;
            $posy = $this->_padding_y;
            break;

        case self::ALIGN_UC:
            $posx = $cx;
            $posy = $this->_padding_y;
            break;

        case self::ALIGN_UR:
            $posx = $srcinfo[0] - $this->_padding_x - $wminfo[0];
            $posy = $this->_padding_y;
            break;

        case self::ALIGN_ML:
            $posx = $this->_padding_x;
            $posy = $cy;
            break;

        case self::ALIGN_MC:
            $posx = $cx;
            $posy = $cy;
            break;

        case self::ALIGN_MR:
            $posx = $srcinfo[0] - $this->_padding_x - $wminfo[0];
            $posy = $cy;
            break;

        case self::ALIGN_LL:
            $posx = $this->_padding_x;
            $posy = $srcinfo[1] - $this->_padding_y - $wminfo[1];
            break;

        case self::ALIGN_LC:
            $posx = $cx;
            break;

        case self::ALIGN_LR:
        default:
            $posx = $srcinfo[0] - $this->_padding_x - $wminfo[0];
            $posy = $srcinfo[1] - $this->_padding_y - $wminfo[1];
            break;
        }
        if( empty($posx) || empty($posy) ) {
            $this->t_error = self::ERROR_OTHER;
            $this->_cleanup();
            return FALSE;
        }

        // Now we're set to merge the two images together
        $res = '';
        if( !empty($this->_text) ) {
            // use this for watermark images we generated
            // from text.
            imagealphablending($this->h_wmimage,FALSE);
            $res = imagecopymerge($this->h_resultimg, $this->h_wmimage, $posx, $posy,
                                  0,0, $wminfo[0],$wminfo[1], $this->_translucency);
        }
        else {
            imagealphablending($this->h_wmimage,FALSE);
            imagesavealpha($this->h_wmimage,TRUE);
            $res = imagecopyresampled($this->h_resultimg, $this->h_wmimage, $posx, $posy,
                                      0,0, $wminfo[0],$wminfo[1], $wminfo[0],$wminfo[1]);
        }
        if( $res === FALSE )  die('copy failed');

        // and save the destination
        if($srcinfo[2] == IMAGETYPE_PNG) {
            imagepng($this->h_resultimg,$destfile,9);
        }
        else {
            imagejpeg($this->h_resultimg,$destfile,100);
        }

        // and we're done.
        $this->_cleanup();
        return TRUE;
    }
} // end of class

#
# EOF
#
?>
