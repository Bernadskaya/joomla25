<?php
/**
 * @version		$Id: uninstall.k2mart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//Load libraries
jimport('joomla.installer.installer');

// Load K2mart language file
$lang = JFactory::getLanguage();
$lang->load('com_k2mart', JPATH_ADMINISTRATOR);

$status = new JObject();
$status->plugins = array();

// Uninstall modules & plugins
$db = JFactory::getDBO();
$modules = $this->manifest->xpath('modules/module');
$plugins = $this->manifest->xpath('plugins/plugin');

foreach ($modules as $module)
{
	$mname = $module->getAttribute('module');
	$client = $module->getAttribute('client');
	$db = JFactory::getDBO();
	$query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($mname)."";
	$db->setQuery($query);
	$IDs = $db->loadResultArray();
	if (count($IDs))
	{
		foreach ($IDs as $id)
		{
			$installer = new JInstaller;
			$result = $installer->uninstall('module', $id);
		}
	}
	$status->modules[] = array('name' => $mname, 'client' => $client, 'result' => $result);
}

foreach ($plugins as $plugin)
{
	$pname = $plugin->getAttribute('plugin');
	$pgroup = $plugin->getAttribute('group');
	$query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($pname)." AND folder = ".$db->Quote($pgroup);
	$db->setQuery($query);
	$IDs = $db->loadResultArray();
	if (count($IDs))
	{
		foreach ($IDs as $id)
		{
			$installer = new JInstaller;
			$result = $installer->uninstall('plugin', $id);
		}
	}
	$status->plugins[] = array('name' => $pname, 'group' => $pgroup, 'result' => $result);
}
?>
<h2>K2mart <?php echo JText::_('K2MART_REMOVAL_STATUS'); ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('K2MART_EXTENSION'); ?></th>
			<th width="30%"><?php echo JText::_('K2MART_STATUS'); ?></th>
		</tr>
	</thead>
	<tbody>
	    <?php if (count($status->modules)) : ?>
		<?php foreach ($status->modules as $module) : ?>
		<tr>
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo ($module['result'])?JText::_('K2MART_REMOVED'):JText::_('K2MART_NOT_REMOVED'); ?></strong></td>
		</tr>
		<?php endforeach;?>
		<?php endif; ?>
		<?php if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('K2MART_PLUGIN'); ?></th>
			<th><?php echo JText::_('K2MART_GROUP'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->plugins as $plugin) : ?>
		<tr>
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo ($plugin['result'])?JText::_('K2MART_REMOVED'):JText::_('K2MART_NOT_REMOVED'); ?></strong></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3"></td>
		</tr>
	</tfoot>
</table>