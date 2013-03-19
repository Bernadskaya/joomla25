<?php
/**
 * @version		$Id: view.html.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2martViewVirtuemart extends JView
{

	function display($tpl = null)
	{
		JHTML::_('core');
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest("{$option}.{$view}.limit", 'limit', 15, 'int');
		$limitstart = $mainframe->getUserStateFromRequest("{$option}.{$view}.limitstart", 'limitstart', 0, 'int');
		$ordering = $mainframe->getUserStateFromRequest("{$option}.{$view}.ordering", 'filter_order', 'virtuemart_product_id', 'cmd');
		$orderingDir = $mainframe->getUserStateFromRequest("{$option}.{$view}.orderingDir", 'filter_order_Dir', 'DESC', 'word');
		$published = $mainframe->getUserStateFromRequest("{$option}.{$view}.published", 'published', '-1', 'cmd');
		$catid = $mainframe->getUserStateFromRequest("{$option}.{$view}.catid", 'catid', 0, 'int');
		$search = $mainframe->getUserStateFromRequest("{$option}.{$view}.search", 'search', '', 'string');
		$search = JString::strtolower($search);

		$model = $this->getModel('virtuemart');
		$model->setState('ordering', $ordering);
		$model->setState('orderingDir', $orderingDir);
		$model->setState('published', $published);
		$model->setState('catid', $catid);
		$model->setState('search', $search);
		$model->setState('limit', $limit);
		$model->setState('limitstart', $limitstart);
		$products = $model->getData();
		$this->assignRef('rows', $products);

		$total = $model->getTotal();
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
		$this->assignRef('pagination', $pagination);

		$filters = array();
		$filters['search'] = $search;
		$filters['ordering'] = $ordering;
		$filters['orderingDir'] = $orderingDir;
		$options = array();
		$options[] = JHTML::_('select.option', -1, JText::_('K2MART_ANY'));
		$options[] = JHTML::_('select.option', 1, JText::_('K2MART_PUBLISHED'));
		$options[] = JHTML::_('select.option', 0, JText::_('K2MART_UNPUBLISHED'));
		$filters['published'] = JHTML::_('select.genericlist', $options, 'published', 'onchange="this.form.submit();"', 'value', 'text', $published);
		$filters['category'] = $model->getCategories();
		if (count(vmconfig::get('active_languages')) > 1)
		{
			$params = JComponentHelper::getParams('com_languages');
			$defaultLanguage = $params->get('site', 'en-GB');
			jimport('joomla.language.helper');
			$lang = $mainframe->getUserStateFromRequest("{$option}.{$view}.vmlang", 'vmlang', $defaultLanguage, 'string');
			$languages = JLanguageHelper::createLanguageList($defaultLanguage, constant('JPATH_SITE'), true);
			$activeVmLangs = (vmconfig::get('active_languages'));
			foreach ($languages as $k => &$joomlaLang)
			{
				if (!in_array($joomlaLang['value'], $activeVmLangs))
					unset($languages[$k]);
			}
			$filters['language'] = JHTML::_('select.genericlist', $languages, 'vmlang', 'onchange="this.form.submit();"', 'value', 'text', $lang, 'vmlang');
		}
		$this->assignRef('filters', $filters);
		$template = $mainframe->getTemplate();
		$this->assignRef('template', $template);
		parent::display($tpl);
	}

}
