<?php
/**
 * @version		$Id: view.html.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martViewMigrator extends JView
{

	function display($tpl = null)
	{
		JHTML::_('behavior.keepalive');
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models'.DS.'categories.php');
		$model = new K2ModelCategories();
		$categories = $model->categoriesTree(NULL, false, false);
		$options = array();
		$options[] = JHTML::_('select.optgroup', JText::_('K2MART_CREATE_NEW_K2_CATEGORY'));
		$options[] = JHTML::_('select.option', 0, JText::_('K2MART_TYPE_CATEGORY_NAME'));
		$options[] = JHTML::_('select.optgroup', JText::_('K2MART_USE_EXISTING_K2_CATEGORY'));
		foreach ($categories as $category)
		{
			$options[] = JHTML::_('select.option', $category->value, $category->text);
		}
		$lists['category'] = JHTML::_('select.genericlist', $options, 'catid', 'class="inputbox"', 'value', 'text', 0);

		$vmLanguages = vmConfig::get('active_languages', array());
		if (count($vmLanguages) > 1)
		{
			jimport('joomla.language.helper');
			$languages = JLanguageHelper::createLanguageList(NULL, constant('JPATH_SITE'), true);
			foreach ($languages as $key => &$language)
			{
				if (!in_array($language['value'], $vmLanguages))
					unset($languages[$key]);
			}
			$lists['language'] = JHTML::_('select.genericlist', $languages, 'vmlang', '', 'value', 'text', NULL, 'vmlang');
		}
		$this->assignRef('lists', $lists);
		$this->loadHelper('html');
		K2martHTMLHelper::title('K2MART_VIRTUEMART_MIGRATOR');
		K2martHTMLHelper::toolbar();
		K2martHTMLHelper::subMenu();
		parent::display($tpl);
	}

	function popup()
	{
		parent::display();
	}

}
