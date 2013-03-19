<?php
/**
 * @version		$Id: template.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright 	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('JPATH_PLATFORM') or die ;

class JFormFieldTemplate extends JFormField
{
	protected $type = 'template';

	protected function getInput()
	{

		jimport('joomla.filesystem.folder');
		$mainframe = JFactory::getApplication();

		$extensionTemplatesPath = JPATH_SITE.DS.'plugins'.DS.'k2'.DS.'k2mart'.DS.'tmpl'.DS.'site';
		$extensionTemplatesFolders = JFolder::folders($extensionTemplatesPath);

		$db = JFactory::getDBO();
		$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
		$db->setQuery($query);
		$template = $db->loadResult();
		$templatePath = JPATH_SITE.DS.'templates'.DS.$template.DS.'html'.DS.'k2mart';

		if (JFolder::exists($templatePath))
		{
			$templateFolders = JFolder::folders($templatePath);
			$folders = @array_merge($templateFolders, $extensionTemplatesFolders);
			$folders = @array_unique($folders);
		}
		else
		{
			$folders = $extensionTemplatesFolders;
		}

		sort($folders);

		$options = array();
		foreach ($folders as $folder)
		{
			$options[] = JHTML::_('select.option', $folder, $folder);
		}

		return JHTML::_('select.genericlist', $options, $this->name, '', 'value', 'text', $this->value);
	}

}
