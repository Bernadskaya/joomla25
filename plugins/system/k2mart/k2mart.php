<?php
/**
 * @version		$Id: k2mart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemK2mart extends JPlugin
{

    function plgSystemK2mart(&$subject, $config)
    {
        jimport('joomla.filesystem.file');
        if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'k2.php') || !JFile::exists(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.php'))
        {
            return;
        }
        parent::__construct($subject, $config);
    }

    function onAfterRoute()
    {

        // Set variables
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $task = JRequest::getCmd('task');
        $layout = JRequest::getCmd('layout');

        // Front-end operations
        if ($mainframe->isSite())
        {
            // If not in Virtuemart return
            if ($option != 'com_virtuemart')
            {
                return;
            }
            // Product details view redirect
            if ($view == 'productdetails' && ($task == '' || $task == 'productdetails') && $layout != 'notify')
            {
                $productID = JRequest::getInt('virtuemart_product_id');
                if ($productID)
                {
                    $db->setQuery("SELECT baseID FROM #__k2mart WHERE referenceID = {$productID}", 0, 1);
                    $itemID = $db->loadResult();
                    if (is_null($itemID))
                    {
                        return;
                    }
                    $query = "SELECT id, alias, catid FROM #__k2_items WHERE id={$itemID}";
                    $db->setQuery($query, 0, 1);
                    $item = $db->loadObject();
                    if (is_null($item))
                    {
                        return;
                    }
                    $db->setQuery("SELECT alias FROM #__k2_categories WHERE id = {$item->catid}", 0, 1);
                    $item->categoryAlias = $db->loadResult();
                    JLoader::register('K2HelperRoute', JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
                    $link = K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryAlias));
                    $link = JRoute::_($link, false);
                    $mainframe->redirect($link);
                }
            }
            // Category and rest views redirect
            $params = JComponentHelper::getParams('com_k2mart');
            if (($view == 'category' || $view == 'categories' || $view == 'virtuemart' || $view == ''))
            {

                $catid = $params->get('catalogRoot');
                JLoader::register('K2HelperRoute', JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
                if ($catid)
                {
                    $link = K2HelperRoute::getCategoryRoute($catid);
                }
                else
                {
                    $menu = $mainframe->getMenu();
                    $active = $menu->getActive();
                    $default = $menu->getDefault();
                    if ($active->id == $default->id)
                    {
                        return;
                    }
                    $link = JURI::base(true);
                }

                $manufacturer = JRequest::getInt('virtuemart_manufacturer_id');

                if ($view == 'category' && $manufacturer)
                {
                    $link .= '&k2martManufacturers[]='.$manufacturer;
                }

                $mainframe->redirect(JRoute::_($link, false));
            }
        }
        // Administration operations
        else
        {

            if ($option == 'com_k2' && ($view == 'items' || $view == ''))
            {

                //Restore ordering value
                JPluginHelper::importPlugin('k2', 'k2mart');
                $dispatcher = JDispatcher::getInstance();
                $result = @$dispatcher->trigger('getAdminProcessFlag');
                $flag = $result[0];
                $ordering = $mainframe->getUserStateFromRequest('com_k2itemsfilter_order', 'filter_order', 'i.id', 'cmd');
                if (!$flag && ($ordering == 'price' || $ordering == 'sku' || $ordering == 'manufacturer'))
                {
                    JRequest::setVar('filter_order', 'i.id');
                    JRequest::setVar('filter_order_Dir', 'desc');
                }

                $cid = JRequest::getVar('cid', array());
                JArrayHelper::toInteger($cid);

                // Load the model class
                jimport('joomla.application.component.model');

                // Load Virtuemart configuration
                require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
                VmConfig::loadConfig();

                // Add tables and models paths
                JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'tables');
                JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'tables');
                JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models', 'K2Model');
                JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'models');
                JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'models', 'K2MartModel');

                // Load the product helper
                JLoader::register('K2martProductHelper', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'helpers'.DS.'product.php');

                // Apply operations
                switch($task)
                {
                    case 'publish' :
                        K2martProductHelper::setPublishedState($cid, 1);
                        break;

                    case 'unpublish' :
                        K2martProductHelper::setPublishedState($cid, 0);
                        break;

                    case 'featured' :
                        K2martProductHelper::setFeaturedState($cid);
                        break;

                    case 'remove' :
                        K2martProductHelper::remove($cid);
                        break;
                }
            }
        }
        return;
    }

}
