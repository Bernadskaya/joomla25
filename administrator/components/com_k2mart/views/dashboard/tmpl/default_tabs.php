<?php
/**
 * @version		$Id: default_tabs.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php echo $this->pane->startPane('k2martPane');?>

	<?php echo $this->pane->startPanel(JText::_('K2MART_ABOUT'), 'k2martTabAbout');?>
		<?php echo JText::_('K2MART_ABOUT_CONTENT'); ?>
	<?php echo $this->pane->endPanel(); ?>
	
	<?php echo $this->pane->startPanel(JText::_('K2MART_SYNC_STATUS'), 'k2martTabStatus');?>
		<table class="adminlist">
			<thead>
				<tr>
					<td class="title"><?php echo JText::_('K2MART_TYPE'); ?></td>
					<td class="title"><?php echo JText::_('K2MART_COUNT'); ?></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo JText::_('K2MART_K2_ITEMS'); ?></td>
					<td><?php echo $this->numOfK2Items; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('K2MART_VIRTUEMART_PRODUCTS'); ?></td>
					<td><?php echo $this->numOfVmProducts; ?></td>
				</tr>
				<tr>
					<td><?php echo JText::_('K2MART_PRODUCTS'); ?></td>
	 				<td><?php echo $this->numOfK2martProducts; ?></td>
				</tr>
			</tbody>
		</table>
	<?php echo $this->pane->endPanel(); ?>
	
<?php echo $this->pane->endPane(); ?>