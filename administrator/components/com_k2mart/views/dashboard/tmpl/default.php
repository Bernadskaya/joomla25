<?php
/**
 * @version		$Id: default.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php echo $this->loadTemplate('ie6'); ?>

<div id="k2martLeftColumn">
	<?php echo $this->charts; ?>
</div>

<div id="k2martRightColumn">
	<div id="cpanel">
		<?php echo $this->loadTemplate('quickicons'); ?>
		<div class="clr"></div>
	</div>

	<?php echo $this->loadTemplate('tabs'); ?>
	
	<div class="clr"></div>
</div>

<div class="clr"></div>

<div id="k2martAdminFooter">
	<?php echo JText::_('K2MART_COPYRIGHTS'); ?>
</div>
