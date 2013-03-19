<?php
/**
 * @version		$Id: install.k2mart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

//Import libraries
jimport('joomla.installer.installer');
jimport('joomla.filesystem.file');

// Load K2mart language file
$lang = JFactory::getLanguage();
$lang->load('com_k2mart', JPATH_ADMINISTRATOR);

$db = JFactory::getDBO();
$status = new JObject();
$status->modules = array();
$status->plugins = array();
$src = $this->parent->getPath('source');

// Install modules
$modules = $this->manifest->xpath('modules/module');

foreach ($modules as $module)
{
	$mname = $module->getAttribute('module');
	$client = $module->getAttribute('client');
	if (is_null($client))
		$client = 'site';
	$path = ($client == 'administrator') ? $src.DS.'administrator'.DS.'modules'.DS.$mname : $src.DS.'modules'.DS.$mname;
	$installer = new JInstaller;
	$result = $installer->install($path);
	$status->modules[] = array('name' => $mname, 'client' => $client, 'result' => $result);
}

// Set the position for specific modules
$query = "UPDATE #__modules SET position='icon', ordering=99, published=1 WHERE module='mod_k2mart'";
$db->setQuery($query);
$db->query();

// Publish specific module
$query = "SELECT id FROM #__modules WHERE `module`='mod_k2mart'";
$db->setQuery($query);
$moduleIDs = $db->loadResultArray();
foreach ($moduleIDs as $id)
{
	$query = "INSERT IGNORE INTO #__modules_menu VALUES({$id}, 0)";
	$db->setQuery($query);
	$db->query();
}

// Install plugins
$plugins = $this->manifest->xpath('plugins/plugin');
foreach ($plugins as $plugin)
{
	$pname = $plugin->getAttribute('plugin');
	$pgroup = $plugin->getAttribute('group');
	$path = $src.DS.'plugins'.DS.$pgroup;
	$installer = new JInstaller;
	$result = $installer->install($path);
	$status->plugins[] = array('name' => $pname, 'group' => $pgroup, 'result' => $result);
	// Enable the plugin
	$query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($pname)." AND folder=".$db->Quote($pgroup);
	$db->setQuery($query);
	$db->query();
}

//Check for requirements
$mainframe = JFactory::getApplication();
if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'k2.php'))
{
	$mainframe->enqueueMessage(JText::_('K2MART_K2_WAS_NOT_FOUND_ON_YOUR_SYSTEM'), 'notice');
}
if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.php'))
{
	$mainframe->enqueueMessage(JText::_('K2MART_VIRTUEMART_K2_WAS_NOT_FOUND_ON_YOUR_SYSTEM'), 'notice');
}
?>
<img src="components/com_k2mart/images/logo.png" alt="K2mart <?php echo JText::_('K2MART_COMPONENT'); ?>" align="right" />
<h2>K2mart <?php echo JText::_('K2MART_INSTALLATION_STATUS'); ?></h2>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title" colspan="2"><?php echo JText::_('K2MART_EXTENSION'); ?></th>
			<th width="30%"><?php echo JText::_('K2MART_STATUS'); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="key" colspan="2">K2mart <?php echo JText::_('K2MART_COMPONENT'); ?></td>
			<td><strong><?php echo JText::_('K2MART_INSTALLED'); ?></strong></td>
		</tr>
		<?php if (count($status->modules)) : ?>
		<tr>
			<th><?php echo JText::_('K2MART_MODULE'); ?></th>
			<th><?php echo JText::_('K2MART_CLIENT'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->modules as $module) : ?>
		<tr>
			<td class="key"><?php echo $module['name']; ?></td>
			<td class="key"><?php echo ucfirst($module['client']); ?></td>
			<td><strong><?php echo ($module['result'])?JText::_('K2MART_INSTALLED'):JText::_('K2MART_NOT_INSTALLED'); ?></strong></td>
		</tr>
		<?php endforeach;?>
		<?php endif;?>
		<?php if (count($status->plugins)) : ?>
		<tr>
			<th><?php echo JText::_('K2MART_PLUGIN'); ?></th>
			<th><?php echo JText::_('K2MART_GROUP'); ?></th>
			<th></th>
		</tr>
		<?php foreach ($status->plugins as $key=>$plugin) : ?>
		<tr>
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo ($plugin['result'])?JText::_('K2MART_INSTALLED'):JText::_('K2MART_NOT_INSTALLED'); ?></strong></td>
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