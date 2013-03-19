<?php
/**
 * @version		$Id: virtuemart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class K2martModelVirtuemart extends JModel
{

	function getData()
	{
		$db = $this->getDBO();
		$query = "SELECT GROUP_CONCAT(categoryData.category_name SEPARATOR ', ') AS category_name, 
		productData.product_name, 
		product.virtuemart_product_id, 
		product.product_sku, product.published AS product_publish 
		FROM #__virtuemart_products AS product
		RIGHT JOIN #__virtuemart_products_".VMLANG." AS productData ON product.virtuemart_product_id = productData.virtuemart_product_id
		LEFT JOIN #__virtuemart_product_categories AS category ON product.virtuemart_product_id = category.virtuemart_product_id
		LEFT JOIN #__virtuemart_categories_".VMLANG." AS categoryData ON category.virtuemart_category_id = categoryData.virtuemart_category_id";
		$conditions = array();
		$conditions[] = "product.product_parent_id=0";
		if ($this->getState('published') != -1)
		{
			$conditions[] = "product.published = ".$this->getState('published');
		}
		if ($this->getState('catid'))
		{
			$conditions[] = "category.virtuemart_category_id=".$this->getState('catid');
		}
		if ($this->getState('search'))
		{
			$conditions[] = "(LOWER(productData.product_name) LIKE ".$db->Quote("%".$db->getEscaped($this->getState('search'), true)."%", false)." OR product.product_sku = ".$db->Quote($this->getState('search')).")";
		}
		if (count($conditions))
		{
			$query .= " WHERE ".implode(' AND ', $conditions);
		}
		$query .= " GROUP BY product.virtuemart_product_id ORDER BY ".$this->getState('ordering')." ".$this->getState('orderingDir');
		$db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$rows = $db->loadObjectList();
		return $rows;
	}

	function getTotal()
	{
		$db = $this->getDBO();
		$query = "SELECT COUNT(DISTINCT product.virtuemart_product_id)
		FROM #__virtuemart_products AS product
		RIGHT JOIN #__virtuemart_products_".VMLANG." AS productData ON product.virtuemart_product_id = productData.virtuemart_product_id
		LEFT JOIN #__virtuemart_product_categories AS category ON product.virtuemart_product_id = category.virtuemart_product_id
		LEFT JOIN #__virtuemart_categories_".VMLANG." AS categoryData ON category.virtuemart_category_id = categoryData.virtuemart_category_id";
		$conditions = array();
		$conditions[] = "product.product_parent_id=0";
		if ($this->getState('published') != -1)
		{
			$conditions[] = "product.published = ".$this->getState('published');
		}
		if ($this->getState('catid'))
		{
			$conditions[] = "category.virtuemart_category_id=".$this->getState('catid');
		}
		if ($this->getState('search'))
		{
			$conditions[] = "(LOWER(productData.product_name) LIKE ".$db->Quote("%".$db->getEscaped($this->getState('search'), true)."%", false)." OR product.product_sku = ".$db->Quote($this->getState('search')).")";
		}
		if (count($conditions))
		{
			$query .= " WHERE ".implode(' AND ', $conditions);
		}
		$db->setQuery($query);
		$total = $db->loadresult();
		return $total;
	}

	function getCategories()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'tables');
		require (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'shopfunctions.php');
		JRequest::setVar('filter_order', 'c.ordering');
		JRequest::setVar('filter_order_Dir', 'ASC');
		$list = ShopFunctions::categoryListTree(array($this->getState('catid')));
		return '<select onchange="this.form.submit();" id="catid" name="catid"><option value="0">'.JText::_('K2MART_ANY').'</option>'.$list.'</select>';
	}

}
