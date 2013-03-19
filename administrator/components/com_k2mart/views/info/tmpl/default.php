<?php
/**
 * @version		$Id: default.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php" method="post" name="adminForm">
	<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="k2martInfoPage">
		<tr>
			<td>
			  <fieldset>
			    <legend><?php echo JText::_('K2MART_SYSTEM');?></legend>
			    <table class="adminlist">
			      <thead>
			        <tr>
			          <th><?php echo JText::_('K2MART_CHECK'); ?></th>
			          <th><?php echo JText::_('K2MART_RESULT');?></th>
			        </tr>
			      </thead>
			      <tfoot>
			        <tr>
			          <th colspan="2"></th>
			        </tr>
			      </tfoot>
			      <tbody>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_WEB_SERVER');?></strong></td>
			          <td><?php echo $this->server; ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_PHP_VERSION');?></strong></td>
			          <td><?php echo $this->php_version; ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_MYSQL_VERSION');?></strong></td>
			          <td><?php echo $this->db_version; ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_MEMORY_LIMIT');?></strong></td>
			          <td><?php echo ini_get('memory_limit'); ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_GD_IMAGE_LIBRARY');?></strong></td>
			          <td><?php echo ($this->gd_check)?JText::_('K2MART_ENABLED'):JText::_('K2MART_DISABLED'); ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_CURL');?></strong></td>
			          <td><?php echo ($this->curl_check)? JText::_('K2MART_ENABLED'):JText::_('K2MART_DISABLED'); ?></td>
			        </tr>
			      </tbody>
			    </table>
			  </fieldset>
			  </td>
			  <td>
			  <fieldset>
			    <legend><?php echo JText::_('K2MART_SOFTWARE');?></legend>
			    <table class="adminlist">
			      <thead>
			        <tr>
			          <th><?php echo JText::_('K2MART_CHECK'); ?></th>
			          <th><?php echo JText::_('K2MART_RESULT');?></th>
			        </tr>
			      </thead>
			      <tfoot>
			        <tr>
			          <th colspan="2"></th>
			        </tr>
			      </tfoot>
			      <tbody>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_K2');?></strong></td>
			          <td><?php echo ($this->k2_check)?JText::_('K2MART_INSTALLED'):JText::_('K2MART_NOT_INSTALLED'); ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_VIRTUEMART');?></strong></td>
			          <td><?php echo ($this->virtuemart_check)?JText::_('K2MART_INSTALLED'):JText::_('K2MART_NOT_INSTALLED'); ?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_SYSTEM_PLUGIN_FOR_K2MART');?></strong></td>
			          <td><?php echo (JFile::exists(JPATH_PLUGINS.DS.'system'.DS.'k2mart'.DS.'k2mart.php'))?JText::_('K2MART_INSTALLED'):JText::_('K2MART_NOT_INSTALLED');?> - <?php echo (JPluginHelper::isEnabled('system', 'k2mart'))?JText::_('K2MART_ENABLED'):JText::_('K2MART_DISABLED');?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_K2_PLUGIN_FOR_K2MART');?></strong></td>
			          <td><?php echo (JFile::exists(JPATH_PLUGINS.DS.'k2'.DS.'k2mart'.DS.'k2mart.php'))?JText::_('K2MART_INSTALLED'):JText::_('K2MART_NOT_INSTALLED');?> - <?php echo (JPluginHelper::isEnabled('k2', 'k2mart'))?JText::_('K2MART_ENABLED'):JText::_('K2MART_DISABLED');?></td>
			        </tr>
			        <tr>
			          <td><strong><?php echo JText::_('K2MART_K2MART_DASHBOARD_MODULE_ADMINISTRATOR');?></strong></td>
			          <td><?php echo (is_null(JModuleHelper::getModule('mod_k2mart')))?JText::_('K2MART_NOT_INSTALLED'):JText::_('K2MART_INSTALLED');?></td>
			        </tr>
			      </tbody>
			    </table>
			  </fieldset>
	    </td>
		</tr>
	</table>
</form>

<div id="k2martAdminFooter">
	<?php echo JText::_('K2MART_COPYRIGHTS'); ?>
</div>