<?php
/**
 * @version		$Id: html.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright 	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martHTMLHelper
{

	public static function title($title, $icon = 'k2mart.png')
	{
		JToolBarHelper::title(JText::_($title), $icon);
	}

	public static function toolbar()
	{
		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view', 'dashboard');
		$task = JRequest::getCmd('task');
		$trash = $mainframe->getUserStateFromRequest("{$option}.{$view}.trash", 'trash', 0, 'int');
		if ($view == 'migrator')
		{
			JToolBarHelper::custom('popup', 'archive', 'archive', 'K2MART_IMPORT', false);
			JToolBarHelper::divider();
		}
		if ($user->authorize('core.admin', 'com_k2mart'))
		{
			JToolBarHelper::preferences('com_k2mart', 550, 875, 'K2MART_SETINGS');
		}
		$toolbar = JToolBar::getInstance('toolbar');
		$toolbar->appendButton('Custom', '<a href="http://www.joomlaworks.net/extensions/commercial-premium/k2mart#documentation" target="_blank"><span class="icon-32-help" title="'.JText::_('K2MART_HELP').'"></span>'.JText::_('K2MART_HELP').'</a>');
	}

	public static function subMenu()
	{
		$params = JComponentHelper::getParams('com_k2mart');
		$view = JRequest::getCmd('view', 'dashboard');
		JSubMenuHelper::addEntry(JText::_('K2MART_DASHBOARD'), 'index.php?option=com_k2mart&view=dashboard', $view == 'dashboard');
		if ($params->get('catalogRoot'))
		{
			JSubMenuHelper::addEntry(JText::_('K2MART_PRODUCTS'), 'index.php?option=com_k2&view=items&filter_category='.$params->get('catalogRoot'));
		}
		JSubMenuHelper::addEntry(JText::_('K2MART_VIRTUEMART_MIGRATOR'), 'index.php?option=com_k2mart&view=migrator', $view == 'migrator');
		JSubMenuHelper::addEntry(JText::_('K2MART_INFORMATION'), 'index.php?option=com_k2mart&view=info', $view == 'info');
	}

	public static function getCategoryFilter($name, $active = NULL, $attributes = '', $specific = false, $showNull = false)
	{
		jimport('joomla.application.component.model');
		JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models');
		$model = JModel::getInstance('Itemlist', 'K2Model');
		$params = JComponentHelper::getParams('com_k2mart');
		$root = $params->get('catalogRoot');
		$tree = $model->getCategoryTree($root);
		$options = array();
		if ($showNull)
		{
			$option = new JObject();
			$option->value = 0;
			$option->text = JText::_('K2MART_SELECT_CATEGORY');
			$options[] = $option;
		}
		$db = JFactory::getDBO();
		$db->setQuery("SELECT id, name, parent FROM #__k2_categories WHERE id IN (".implode(',', $tree).")");
		$rows = $db->loadObjectList();
		$children = array();
		if ($rows)
		{
			foreach ($rows as $row)
			{
				$row->title = $row->name;
				$row->parent_id = $row->parent;
				$index = $row->parent;
				$list = @$children[$index] ? $children[$index] : array();
				array_push($list, $row);
				$children[$index] = $list;
			}
		}
		$categories = JHTML::_('menu.treerecurse', (isset($rows[0]->parent)) ? $rows[0]->parent : 0, '', array(), $children, 9999, 0, 0);
		foreach ($categories as $category)
		{
			if ($specific === false || in_array($category->id, $specific))
			{
				$category->treename = JString::trim($category->treename);
				$category->treename = JString::str_ireplace('&#160;&#160;', '- ', $category->treename);
				$category->treename = JString::str_ireplace('- ', ' ', $category->treename, 1);
				$options[] = JHTML::_('select.option', $category->id, $category->treename);
			}

		}
		return JHTML::_('select.genericlist', $options, $name, $attributes, 'value', 'text', $active);
	}

	public static function getAuthorFilter($name, $active)
	{
		$db = &JFactory::getDBO();
		$query = "SELECT id AS value, name AS text FROM #__users WHERE block = 0 ORDER BY name";
		$db->setQuery($query);
		$users[] = JHTML::_('select.option', '0', JText::_('K2MART_SELECT_AUTHOR'));
		$users = array_merge($users, $db->loadObjectList());
		$filter = JHTML::_('select.genericlist', $users, $name, 'class="inputbox" size="1" ', 'value', 'text', $active);
		return $filter;
	}

	public static function getPublishedFilter($name = 'published', $active)
	{
		$options = array();
		$options[] = JHTML::_('select.option', -1, JText::_('K2MART_SELECT_PUBLISHING_STATE'));
		$options[] = JHTML::_('select.option', 1, JText::_('K2MART_PUBLISHED'));
		$options[] = JHTML::_('select.option', 0, JText::_('K2MART_UNPUBLISHED'));
		return JHTML::_('select.genericlist', $options, $name, '', 'value', 'text', $active);
	}

	public static function getFeaturedFilter($name = 'featured', $active)
	{
		$options = array();
		$options[] = JHTML::_('select.option', -1, JText::_('K2MART_SELECT_FEATURED_STATE'));
		$options[] = JHTML::_('select.option', 1, JText::_('K2MART_FEATURED'));
		$options[] = JHTML::_('select.option', 0, JText::_('K2MART_NOT_FEATURED'));
		return JHTML::_('select.genericlist', $options, $name, '', 'value', 'text', $active);
	}

	public static function getTrashFilter($name = 'trash', $active)
	{
		$options = array();
		$options[] = JHTML::_('select.option', 0, JText::_('K2MART_CURRENT'));
		$options[] = JHTML::_('select.option', 1, JText::_('K2MART_TRASHED'));
		return JHTML::_('select.genericlist', $options, $name, '', 'value', 'text', $active);
	}

	public static function getManufacturerFilter($name = 'manufacturer', $active, $attributes = '', $showNull = false)
	{
		$mainframe = JFactory::getApplication();
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'models');
		$model = JModel::getInstance('Manufacturers', 'K2martModel');
		$model->setState('ordering', 'mf_name');
		$model->setState('orderingDir', 'ASC');
		if ($mainframe->isSite())
		{
			$model->setState('published', 1);
		}
		$rows = $model->getData();
		$options = array();
		if ($showNull)
		{
			$options[] = JHTML::_('select.option', 0, JText::_('K2MART_SELECT_MANUFACTURER'));
		}
		foreach ($rows as $row)
		{
			$options[] = JHTML::_('select.option', $row->virtuemart_manufacturer_id, $row->mf_name);
		}
		return JHTML::_('select.genericlist', $options, $name, $attributes, 'value', 'text', $active);
	}

	public static function getRelationFilter($name = 'relation', $active, $attributes = '', $showNull = false)
	{
		$options = array();
		if ($showNull)
		{
			$options[] = JHTML::_('select.option', -1, JText::_('K2MART_SELECT_RELATION'));
		}
		$options[] = JHTML::_('select.option', 0, JText::_('K2MART_PARENTS'));
		$options[] = JHTML::_('select.option', 1, JText::_('K2MART_CHILDREN'));
		return JHTML::_('select.genericlist', $options, $name, $attributes, 'value', 'text', $active);
	}


	public static function getLanguageFilter($name = 'language', $active)
	{
		$languages = JHTML::_('contentlanguage.existing', true, true);
		array_unshift($languages, JHTML::_('select.option', '', JText::_('K2MART_SELECT_LANGUAGE')));
		return JHTML::_('select.genericlist', $languages, $name, '', 'value', 'text', $active);
	}

	public static function stateToggler(&$row, $i, $property = 'published', $alts = array('K2MART_PUBLISHED', 'K2MART_NOT_PUBLISHED'), $titles = array('K2MART_UNPUBLISH', 'K2MART_PUBLISH'), $task = null)
	{
		$mainframe = &JFactory::getApplication();
		$iconsPath = JURI::base(true).'/templates/'.$mainframe->getTemplate().'/images/admin/';
		$icon = $row->$property ? 'tick.png' : 'publish_x.png';
		$alt = $row->$property ? $alts[0] : $alts[1];
		$title = $row->$property ? $titles[0] : $titles[1];
		if (is_null($task))
		{
			$task = $property;
		}
		$html = '
        <a href="javascript:void(0);" onclick="return listItemTask(\'cb'.$i.'\',\''.$task.'\')" title="'.JText::_($title).'">
        <img src="'.$iconsPath.$icon.'" border="0" alt="'.JText::_($alt).'" /></a>';
		return $html;
	}

}
