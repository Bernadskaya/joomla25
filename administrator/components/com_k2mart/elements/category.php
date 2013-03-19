<?php
/**
 * @version		$Id: category.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright 	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('JPATH_PLATFORM') or die ;

class JFormFieldCategory extends JFormField
{
	protected $type = 'category';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__k2_categories WHERE trash = 0 ORDER BY parent, ordering";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$children = array();
		if ($rows)
		{
			foreach ($rows as $row)
			{
				$row->title = $row->name;
				$row->parent_id = $row->parent;
				$pt = $row->parent;
				$list = @$children[$pt] ? $children[$pt] : array();
				array_push($list, $row);
				$children[$pt] = $list;
			}
		}
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
		$rows = array();
		$rows[] = JHTML::_('select.option', '0', JText::_('K2_NONE_ONSELECTLISTS'));
		foreach ($list as $item)
		{
			$item->treename = JString::str_ireplace('&#160;', ' -', $item->treename);
			$rows[] = JHTML::_('select.option', $item->id, $item->treename);
		}
		return JHTML::_('select.genericlist', $rows, $this->name, '', 'value', 'text', $this->value);
	}

}
