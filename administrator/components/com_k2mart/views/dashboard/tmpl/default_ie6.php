<?php
/**
 * @version		$Id: default_ie6.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<!--[if lt IE 7]>
<div style="border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;">
    <div style="position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;">
        <a href="#" onclick="javascript:this.parentNode.parentNode.style.display='none'; return false;"><img src="http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg" style="border: none;" alt="<?php echo JText::_('Close this notice')?>"/></a>
    </div>
    <div style="width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;">
        <div style="width: 75px; float: left;">
            <img src="http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg" alt="<?php echo JText::_('Warning!')?>"/>
        </div>
        <div style="width: 275px; float: left; font-family: Arial, sans-serif;">
            <div style="font-size: 14px; font-weight: bold; margin-top: 12px;">
            	<?php echo JText::_('You are using an outdated browser')?>
            </div>
            <div style="font-size: 12px; margin-top: 6px; line-height: 12px;">
                <?php echo JText::_('For a better experience using this site, please upgrade to a modern web browser.')?>
            </div>
        </div>
        <div style="width: 75px; float: left;">
            <a href="http://www.firefox.com" target="_blank"><img src="http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg" style="border: none;" alt="<?php echo JText::_('Get Firefox 3.5')?>"/></a>
        </div>
        <div style="width: 75px; float: left;">
            <a href="http://www.browserforthebetter.com/download.html" target="_blank"><img src="http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg" style="border: none;" alt="<?php echo JText::_('Get Internet Explorer 8')?>"/></a>
        </div>
        <div style="width: 73px; float: left;">
            <a href="http://www.apple.com/safari/download/" target="_blank"><img src="http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg" style="border: none;" alt="<?php echo JText::_('Get Internet Explorer 8')?>"/></a>
        </div>
        <div style="float: left;">
            <a href="http://www.google.com/chrome" target="_blank"><img src="http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg" style="border: none;" alt="<?php echo JText::_('Get Google Chrome')?>"/></a>
        </div>
    </div>
</div>
<br />
<br />
<![endif]-->
