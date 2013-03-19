<?php
/**
 * @version		$Id: categories.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martModelCategories extends JModel
{

	function getData()
	{
		$db = $this->getDBO();
		$query = "SELECT category.*,
		`group`.title AS groupName, 
		extraFieldsGroup.name as extra_fields_group 
		FROM #__k2_categories as category 
		LEFT JOIN #__viewlevels AS `group` ON `group`.id = category.access 
		LEFT JOIN #__k2_extra_fields_groups AS extraFieldsGroup ON extraFieldsGroup.id = category.extraFieldsGroup 
		RIGHT JOIN #__k2mart AS k2mart ON k2mart.baseID = category.id";
		$conditions = array();
		if ($this->getState('trash') != -1)
		{
			$conditions[] = "category.trash = ".(int)$this->getState('trash');
		}
		if ($this->getState('published') != -1)
		{
			$conditions[] = "category.published = ".(int)$this->getState('published');
		}
		if ($this->getState('language'))
		{
			$conditions[] = "category.language IN (".$db->Quote($this->getState('language')).",".$db->Quote('*').")";
		}
		if ($this->getState('search'))
		{
			$conditions[] = "LOWER(category.name) LIKE ".$db->Quote('%'.$db->getEscaped($this->getState('search'), true).'%', false);
		}
		$conditions[] = "k2mart.`type` = 'category' AND k2mart.extension = 'com_virtuemart'";
		if (count($conditions))
		{
			$query .= " WHERE ".implode(' AND ', $conditions);
		}
		$query .= " ORDER BY ".$this->getState('ordering', 'category.id')." ".$this->getState('orderingDir', 'DESC');
		$db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$rows = $db->loadObjectList();
		return $rows;
	}

	function getTotal()
	{
		$db = $this->getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_categories AS category
		RIGHT JOIN #__k2mart AS k2mart ON k2mart.baseID = category.id";
		$conditions = array();
		if ($this->getState('trash') != -1)
		{
			$conditions[] = "category.trash = ".(int)$this->getState('trash');
		}
		if ($this->getState('published') != -1)
		{
			$conditions[] = "category.published = ".(int)$this->getState('published');
		}
		if ($this->getState('language'))
		{
			$conditions[] = "category.language IN (".$db->Quote($this->getState('language')).",".$db->Quote('*').")";
		}
		if ($this->getState('search'))
		{
			$conditions[] = "LOWER(category.name) LIKE ".$db->Quote('%'.$db->getEscaped($this->getState('search'), true).'%', false);
		}
		$conditions[] = "k2mart.`type` = 'category' AND k2mart.extension = 'com_virtuemart'";
		if (count($conditions))
		{
			$query .= " WHERE ".implode(' AND ', $conditions);
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		return $total;
	}

	function getCategoriesTree()
	{
		$db = JFactory::getDBO();
		$this->setState('published', -1);
		$this->setState('trash', -1);
		$this->setState('ordering', 'category.parent, category.ordering');
		$this->setState('orderingDir', '');
		$rows = $this->getData();
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
			$category->treename = JString::trim($category->treename);
			$category->treename = JString::str_ireplace('&#160;&#160;', '- ', $category->treename);
			$category->treename = JString::str_ireplace('- ', ' ', $category->treename, 1);
		}
		return $categories;
	}

	function getReferenceID()
	{
		$db = $this->getDBO();
		$query = "SELECT referenceID FROM #__k2mart WHERE baseID = ".(int)$this->getState('baseID')." AND `type` = 'category' AND extension = 'com_virtuemart'";
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

}
