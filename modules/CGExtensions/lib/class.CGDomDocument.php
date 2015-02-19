<?php

/**
 * A simple domdocument extension that includes abilities for js like innerHTML functionality in its elements
 *
 * @package CGExtensions
 * @category Utilities
 * @author  calguy1000 <calguy1000@cmsmadesimple.org>
 * @copyright Copyright 2010 by Robert Campbell
 */

/*
 * A simple domdocument extension that includes abilities for js like innerHTML functionality in its elements
 *
 * @package CGExtensions
 */
class CGDomDocument extends DOMDocument
{
    /**
     * Constructor.
     *
     * @param string $version The version string
     * @param string $encoding The encoding string
     */
    public function __construct($version = '', $encoding = '')
    {
        parent::__construct($version,$encoding);

        $this->registerNodeClass('DOMElement', 'JSLikeHTMLElement');
    }
}

?>
