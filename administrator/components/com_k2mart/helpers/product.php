<?php
/**
 * @version		$Id: product.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright 	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martProductHelper
{

	function prepare(&$products)
	{

		// Get user
		$user = JFactory::getUser();

		// Get null date
		$db = JFactory::getDBO();
		$nullDate = $db->getNullDate();

		// Get trash state
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$trash = $mainframe->getUserStateFromRequest("{$option}.{$view}.trash", 'trash', -1, 'int');

		// Require Virtuemart classes for price display
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'currencydisplay.php');
		$currency = CurrencyDisplay::getInstance();

		// Cast the input to array
		if (!is_array($products))
		{
			$rows = array($products);
		}
		else
		{
			$rows = $products;
		}

		// Prepare the products
		foreach ($rows as $key => $product)
		{
			$product->parity = ($key % 2);
			$product->key = $key + 1;
			$product->checkout = JHTML::_('grid.checkedout', $product, $key);
			$product->price = $currency->priceDisplay($product->price, (int)$product->currency, true);
			$product->featuredToggler = K2martHTMLHelper::stateToggler($product, $key, 'featured', array('K2MART_FEATURED', 'K2MART_NOT_FEATURED'), array('K2MART_REMOVE_FEATURED_FLAG', 'K2MART_FLAG_AS_FEATURED'));
			$product->publishedToggler = K2martHTMLHelper::stateToggler($product, $key, 'published', array('K2MART_PUBLISHED', 'K2MART_UNPUBLISHED'), array('K2MART_UNPUBLISH', 'K2MART_PUBLISH'));
			if (JTable::isCheckedOut($user->get('id'), $product->checked_out) || $trash == 1)
			{
				$product->link = false;
				$product->featuredToggler = strip_tags($product->featuredToggler, '<img>');
				$product->publishedToggler = strip_tags($product->publishedToggler, '<img>');
			}
			else
			{
				$product->link = JRoute::_('index.php?option=com_k2&view=item&cid='.$product->id);
			}
			$product->categoryLink = JRoute::_('index.php?option=com_k2&view=category&cid='.$product->catid);
			if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$product->id).'_XL.jpg'))
			{
				$product->image = JURI::root(true).'/media/k2/items/cache/'.md5("Image".$product->id).'_XL.jpg';
			}
			else
			{
				$product->image = false;
			}
			$product->created = JHTML::_('date', $product->created);
			$product->modified = ($product->modified == $nullDate) ? JText::_('K2MART_NEVER') : JHTML::_('date', $product->modified);
		}
	}

	function setPublishedState($cid, $state = null)
	{
		JArrayHelper::toInteger($cid);
		$item = JTable::getInstance('K2Item', 'Table');
		$model = JModel::getInstance('Product', 'VirtuemartModel');
		$product = $model->getTable('products');
		$model = JModel::getInstance('Products', 'K2martModel');
		foreach ($cid as $id)
		{
			$item->load($id);
			if (is_null($state))
			{
				if ($item->published == 1)
				{
					$state = 0;
				}
				else
				{
					$state = 1;
				}
			}
			$model->setState('baseID', $id);
			$referenceID = $model->getReferenceID();
			$product->publish(array($referenceID), $state);
		}
	}

	function setFeaturedState($cid, $state = null)
	{
		JArrayHelper::toInteger($cid);
		$item = JTable::getInstance('K2Item', 'Table');
		$model = JModel::getInstance('Products', 'K2martModel');
		foreach ($cid as $id)
		{
			$item->load($id);
			if (is_null($state))
			{
				if ($item->featured == 1)
				{
					$state = 0;
				}
				else
				{
					$state = 1;
				}
			}
			$model->setState('baseID', $item->id);
			$referenceID = $model->getReferenceID();
			$db = JFactory::getDBO();
			$db->setQuery("UPDATE #__virtuemart_products SET product_special = ".(int)$state." WHERE virtuemart_product_id = ".$referenceID);
			$db->query();
		}
	}

	function setTrashState($cid, $state = 0)
	{
		JArrayHelper::toInteger($cid);
		$row = JTable::getInstance('K2Item', 'Table');
		foreach ($cid as $id)
		{
			$row->load($id);
			$row->trash = $state;
			$row->store();
		}
	}

	function move($cid, $catid)
	{
		JArrayHelper::toInteger($cid);
		$item = JTable::getInstance('K2Item', 'Table');
		$model = JModel::getInstance('Product', 'VirtuemartModel');
		$product = $model->getTable('products');
		$model = JModel::getInstance('Products', 'K2martModel');
		$categoriesModel = JModel::getInstance('Categories', 'K2martModel');
		$db = JFactory::getDBO();
		foreach ($cid as $id)
		{
			$item->load($id);
			$item->catid = $catid;
			$item->store();
			$model->setState('baseID', $item->id);
			$referenceID = $model->getReferenceID();
			$product->load($referenceID);
			$db->setQuery("DELETE FROM #__virtuemart_product_categories WHERE virtuemart_product_id = ".$referenceID);
			$db->query();
			$categoriesModel->setState('baseID', $catid);
			$categoryReferenceID = $categoriesModel->getReferenceID();
			$db->setQuery("INSERT INTO #__virtuemart_product_categories VALUES('', ".$referenceID.", ".$categoryReferenceID.", '')");
			$db->query();
		}
	}

	function remove($cid)
	{
		if (count($cid))
		{
			// Remove the Virtuemart product
			$vmModel = JModel::getInstance('Product', 'VirtuemartModel');
			$model = JModel::getInstance('Products', 'K2martModel');
			foreach ($cid as $id)
			{
				// Get the reference ID
				$model->setState('baseID', $id);
				$referenceID = $model->getReferenceID();

				// Remove product from Virtuemart
				$vmModel->remove(array($referenceID));

			}

			// Remove the K2mart assignment
			$db = JFactory::getDBO();
			$db->setQuery("DELETE FROM #__k2mart WHERE baseID IN (".implode(',', $cid).")");
			$db->query();
		}
	}

}
