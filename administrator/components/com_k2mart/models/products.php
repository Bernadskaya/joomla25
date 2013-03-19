<?php
/**
 * @version		$Id: products.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martModelProducts extends JModel
{

	function getData()
	{
		$db = $this->getDBO();
		$query = "SELECT item.*,
		acl.title AS viewLevel, 
		category.name AS categoryName, 
		user1.name AS editor, 
		user2.name AS author, 
		user3.name AS moderator, 
		product.product_sku AS sku, 
		prices.product_price AS price,
		prices.product_currency AS currency,
		manufacturers.virtuemart_manufacturer_id,
		manufacturers.mf_name AS manufacturer
		FROM #__k2_items AS item 
		LEFT JOIN #__k2_categories AS category ON category.id = item.catid
		LEFT JOIN #__viewlevels AS acl ON acl.id = item.access 
		LEFT JOIN #__users AS user1 ON user1.id = item.checked_out 
		LEFT JOIN #__users AS user2 ON user2.id = item.created_by 
		LEFT JOIN #__users AS user3 ON user3.id = item.modified_by 
		RIGHT JOIN #__k2mart AS k2mart ON k2mart.baseID = item.id 
		LEFT JOIN #__virtuemart_products AS product ON product.virtuemart_product_id = k2mart.referenceID 
		LEFT JOIN #__virtuemart_product_prices AS prices ON  product.virtuemart_product_id = prices.virtuemart_product_id
		LEFT JOIN #__virtuemart_product_manufacturers AS manufacturersXref ON  product.virtuemart_product_id = manufacturersXref.virtuemart_product_id
		LEFT JOIN #__virtuemart_manufacturers_".VMLANG." AS manufacturers ON manufacturersXref.virtuemart_manufacturer_id = manufacturers.virtuemart_manufacturer_id";
		$conditions = array();
		$conditions[] = "item.trash = ".(int)$this->getState('trash');
		if ($this->getState('published') != -1)
		{
			$conditions[] = "item.published = ".(int)$this->getState('published');
		}
		if ($this->getState('category'))
		{
			$category = $this->getState('category');
			if (is_array($category))
			{
				JArrayHelper::toInteger($category);
				$conditions[] = "item.catid IN (".implode(',', $category).")";
			}
			else
			{
				$conditions[] = "item.catid = ".(int)$category;
			}
		}
		if ($this->getState('author'))
		{
			$conditions[] = "item.created_by = ".(int)$this->getState('author');
		}
		if ($this->getState('manufacturer'))
		{
			$conditions[] = "manufacturersXref.virtuemart_manufacturer_id = ".(int)$this->getState('manufacturer');
		}
		if ($this->getState('featured') != -1)
		{
			$conditions[] = "item.featured = ".(int)$this->getState('featured');
		}
		if ($this->getState('language'))
		{
			$conditions[] = "item.language IN (".$db->Quote($this->getState('language')).",".$db->Quote('*').")";
		}
		if ($this->getState('search'))
		{
			$conditions[] = "(
			LOWER(item.title) LIKE ".$db->Quote('%'.$db->getEscaped($this->getState('search'), true).'%', false)." OR 
			LOWER(product.product_sku) LIKE ".$db->Quote('%'.$db->getEscaped($this->getState('search'), true).'%', false).")";
		}
		if (count($conditions))
		{
			$query .= " WHERE ".implode(' AND ', $conditions);
		}
		$query .= " ORDER BY ".$this->getState('ordering', 'item.id')." ".$this->getState('orderingDir', 'DESC');
		$db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$rows = $db->loadObjectList();
		return $rows;
	}

	function getTotal()
	{
		$db = $this->getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items AS item
		RIGHT JOIN #__k2mart AS k2mart ON k2mart.baseID = item.id 
		LEFT JOIN #__virtuemart_products AS product ON product.virtuemart_product_id = k2mart.referenceID 
		LEFT JOIN #__virtuemart_product_manufacturers AS manufacturersXref ON  product.virtuemart_product_id = manufacturersXref.virtuemart_product_id";
		$conditions = array();
		$conditions[] = "item.trash = ".(int)$this->getState('trash');
		if ($this->getState('published') != -1)
		{
			$conditions[] = "item.published = ".(int)$this->getState('published');
		}
		if ($this->getState('category'))
		{
			$category = $this->getState('category');
			if (is_array($category))
			{
				JArrayHelper::toInteger($category);
				$conditions[] = "item.catid IN (".implode(',', $category).")";
			}
			else
			{
				$conditions[] = "item.catid = ".(int)$category;
			}
		}
		if ($this->getState('author'))
		{
			$conditions[] = "item.created_by = ".(int)$this->getState('author');
		}
		if ($this->getState('manufacturer'))
		{
			$conditions[] = "manufacturersXref.virtuemart_manufacturer_id = ".(int)$this->getState('manufacturer');
		}
		if ($this->getState('featured') != -1)
		{
			$conditions[] = "item.featured = ".(int)$this->getState('featured');
		}
		if ($this->getState('language'))
		{
			$conditions[] = "item.language IN (".$db->Quote($this->getState('language')).",".$db->Quote('*').")";
		}
		if ($this->getState('search'))
		{
			$conditions[] = "(
			LOWER(item.title) LIKE ".$db->Quote('%'.$db->getEscaped($this->getState('search'), true).'%', false)." OR 
			LOWER(product.product_sku) LIKE ".$db->Quote('%'.$db->getEscaped($this->getState('search'), true).'%', false).")";
		}
		if (count($conditions))
		{
			$query .= " WHERE ".implode(' AND ', $conditions);
		}
		$db->setQuery($query);
		$total = $db->loadResult();
		return $total;
	}

	function getReferenceID()
	{
		$db = $this->getDBO();
		$query = "SELECT referenceID FROM #__k2mart WHERE baseID = ".(int)$this->getState('baseID');
		$db->setQuery($query, 0, 1);
		return $db->loadResult();
	}

}
