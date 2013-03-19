<?php
/**
 * @version		$Id: migrator.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martControllerMigrator extends JController
{

    public function display($cachable = false, $urlparams = false)
    {
        jimport('joomla.filesystem.file');
        $mainframe = JFactory::getApplication();
        if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'k2.php'))
        {
            $mainframe->enqueueMessage(JText::_('K2MART_K2_WAS_NOT_FOUND_ON_YOUR_SYSTEM'), 'error');
        }
        if (!JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'admin.virtuemart.php'))
        {
            $mainframe->enqueueMessage(JText::_('K2MART_VIRTUEMART_K2_WAS_NOT_FOUND_ON_YOUR_SYSTEM'), 'error');
        }
        parent::display();
    }

    function popup()
    {
        $session = JFactory::getSession();
        $session->set('k2martImageParams');
        $session->set('k2martImportParams');
        $session->set('k2martImportParent');
        $view = $this->getView('migrator', 'html');
        $view->setLayout('import');
        $view->popup();
    }

    function import()
    {
        JRequest::checkToken() or jexit('Invalid Token');
        jimport('joomla.filesystem.file');
        jimport('joomla.html.parameter');
        jimport('joomla.utilities.xmlelement');
        ini_set('display_errors', 0);
        error_reporting(0);
        set_time_limit(0);
        $date = JFactory::getDate();
        $document = JFactory::getDocument();
        $document->setName('k2mart_'.$date->toUnix());
        $type = JRequest::getCmd('type');
        $response = $this->$type();
        echo json_encode($response);
    }

    function importCategories()
    {

        $session = JFactory::getSession();
        $date = JFactory::getDate();
        $db = JFactory::getDBO();
        $params = JComponentHelper::getParams('com_k2');
        $id = JRequest::getInt('id');
        $catid = JRequest::getInt('catid');
        $lang = JRequest::getString('vmlang', '*');

        // Create the response object
        $response = new JObject();
        $response->task = 'importCategories';

        // If migrator started now then set some variables for later usage
        if (!$id)
        {
            $xml = new JXMLElement(JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models'.DS.'category.xml'));
            $categoryParams = new JParameter('');
            foreach ($xml->params as $paramGroup)
            {
                foreach ($paramGroup->param as $param)
                {
                    if ($param->getAttribute('type') != 'spacer' && $param->getAttribute('name'))
                    {
                        $categoryParams->set($param->getAttribute('name'), $param->getAttribute('default'));
                    }
                }
            }
            if ($catid)
            {
                $categoryParams->set('inheritFrom', $catid);
                $parent = $catid;
                $query = "SELECT params FROM #__k2_categories WHERE id=".$catid;
                $db->setQuery($query, 0, 1);
                $cparams = new JParameter($db->loadResult());
                $params->merge($cparams);
            }
            else
            {
                $K2Category = JTable::getInstance('K2Category', 'Table');
                $K2Category->name = JRequest::getString('categoryName', 'Products');
                $K2Category->published = 1;
                $K2Category->access = 1;
                $K2Category->trash = 0;
                $K2Category->parent = 0;
                $K2Category->ordering = $K2Category->getNextOrder('parent=0');
                $params->merge($categoryParams);
                $K2Category->params = $categoryParams->toString();
                $K2Category->language = $lang;
                $K2Category->check();
                $K2Category->store();
                $categoryParams->set('inheritFrom', $K2Category->id);
                $parent = $K2Category->id;
            }
            $session->set('k2martImageParams', $params);
            $session->set('k2martImportParams', $categoryParams);
            $session->set('k2martImportParent', $parent);
        }

        // Get the category
        $query = "SELECT category.*, categoryData.*, xref.category_parent_id FROM #__virtuemart_categories AS category LEFT JOIN #__virtuemart_category_categories AS xref ON category.virtuemart_category_id = xref.category_child_id LEFT JOIN #__virtuemart_categories_".VMLANG." AS categoryData ON category.virtuemart_category_id = categoryData.virtuemart_category_id WHERE category.virtuemart_category_id > {$id}";
        if (JRequest::getBool('ignoreUnpublished'))
        {
            $query .= " AND category.published = 1";
        }
        $query .= " ORDER BY category.virtuemart_category_id ASC";
        $db->setQuery($query, 0, 1);
        $category = $db->loadObject();

        // If we have finished with the categories then go to the next step
        if (is_null($category))
        {
            $response->id = 0;
            $response->message = JText::_('K2MART_IMPORT_OF_CATEGORIES_COMPLETED');
            $response->task = 'buildCategoriesTree';
        }
        elseif (is_null($category->virtuemart_category_id))
        {
            $id++;
            $response->id = $id;
        }
        else
        {
            // Store/Update the category in K2
            $K2Category = JTable::getInstance('K2Category', 'Table');
            $K2Category->name = $category->category_name;
            $K2Category->description = $category->category_description;
            $K2Category->published = $category->published;
            $K2Category->access = 1;
            $K2Category->trash = 0;
            $K2Category->ordering = $category->ordering;
            if ($category->category_parent_id)
            {
                $K2Category->parent = $category->category_parent_id;
            }
            else
            {
                $K2Category->parent = 0;
            }
            $K2Category->image = null;

            // Get category image
            $vmMediaModel = JModel::getInstance('Media', 'VirtuemartModel');
            $media = $vmMediaModel->getFiles(true, false, null, $category->virtuemart_category_id);

            if (count($media) && JRequest::getBool('proccessImages'))
            {
                $image = realpath(JPATH_SITE.DS.$media[0]->file_url);
                if (JFile::exists($image))
                {
                    $K2Category->image = basename($image);
                    JFile::copy($image, JPATH_SITE.DS.'media'.DS.'k2'.DS.'categories'.DS.$K2Category->image);
                }
            }

            $categoryParams = $session->get('k2martImportParams');
            $K2Category->params = is_null($categoryParams) ? '' : $categoryParams->toString();
            $K2Category->language = $lang;
            $K2Category->check();
            $K2Category->store();

            // Create category tag
            $K2Tag = JTable::getInstance('K2Tag', 'Table');
            $K2Tag->name = $category->category_name;
            $K2Tag->published = $category->published;
            $K2Tag->check();
            $K2Tag->store();

            // Store the mapping between K2 and Virtuemart categories and tags into session because we will use them in the next steps.
            if (!$id)
            {
                $session->set('k2martImportMapping', array());
            }
            $mapping = $session->get('k2martImportMapping', array());
            $mapping['categories'][$category->virtuemart_category_id] = $K2Category->id;
            $mapping['tags'][$category->virtuemart_category_id] = $K2Tag->id;
            $session->set('k2martImportMapping', $mapping);

            // Update the response object
            $response->id = $category->virtuemart_category_id;
            $response->message = JText::_('K2MART_IMPORTED_CATEGORY').': '.JFilterOutput::cleanText($category->category_name);
        }

        return $response;
    }

    function buildCategoriesTree()
    {
        $session = JFactory::getSession();
        $db = JFactory::getDBO();
        $rows = array();
        $mapping = $session->get('k2martImportMapping', array('categories' => array()));
        foreach ($mapping['categories'] as $referenceID => $baseID)
        {
            $row = new JObject();
            $row->baseID = $baseID;
            $row->referenceID = $referenceID;
            $rows[] = $row;
        }
        $xref = array();
        $xref[0] = $session->get('k2martImportParent');
        foreach ($rows as $row)
        {
            $xref[$row->referenceID] = $row->baseID;
        }
        foreach ($rows as $row)
        {
            $db->setQuery("SELECT parent FROM #__k2_categories WHERE id = ".$row->baseID);
            $result = $db->loadResult();
            if (array_key_exists($result, $xref))
            {
                $db->setQuery("UPDATE #__k2_categories SET parent = ".$xref[$result]." WHERE id = ".$row->baseID);
                $db->query();
            }
        }

        // Create the response object
        $response = new JObject();
        $response->task = 'importProducts';
        $response->id = 0;
        $response->message = JText::_('K2MART_BUILDING_CATEGORIES_TREE_COMPLETED');
        return $response;
    }

    function importProducts()
    {

        $session = JFactory::getSession();
        $user = JFactory::getUser();
        $date = JFactory::getDate();
        $db = JFactory::getDBO();
        $id = JRequest::getInt('id');
        $lang = JRequest::getString('vmlang', '*');

        // Set the response object
        $response = new JObject();
        $response->task = 'importProducts';

        //Get K2 default item params
        $xml = new JXMLElement(JFile::read(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'models'.DS.'item.xml'));
        $itemParams = new JParameter('');
        foreach ($xml->params as $paramGroup)
        {
            foreach ($paramGroup->param as $param)
            {
                if ($param->getAttribute('type') != 'spacer' && $param->getAttribute('name'))
                {
                    $itemParams->set($param->getAttribute('name'), $param->getAttribute('default'));
                }
            }
        }
        $itemParams = $itemParams->toString();
        $params = $session->get('k2martImageParams');

        // Get the product
        $query = "SELECT product.*, productData.* FROM #__virtuemart_products AS product LEFT JOIN #__virtuemart_products_".VMLANG." AS productData ON product.virtuemart_product_id = productData.virtuemart_product_id WHERE product.virtuemart_product_id > {$id} ";
        if (JRequest::getBool('ignoreUnpublished'))
        {
            $query .= " AND product.published = 1";
        }
        $query .= " ORDER BY product.virtuemart_product_id ASC";
        $db->setQuery($query, 0, 1);
        $product = $db->loadObject();

        // If we have finished with the products then stop
        if (is_null($product))
        {
            $response->id = 0;
            $response->message = JText::_('K2MART_IMPORT_COMPLETED');
            $response->status = 1;
        }
        elseif (is_null($product->virtuemart_product_id))
        {
            $id++;
            $response->id = $id;
        }
        else
        {
            $db->setQuery("SELECT virtuemart_category_id FROM #__virtuemart_product_categories WHERE virtuemart_product_id = ".(int)$product->virtuemart_product_id);
            $categories = $db->loadObjectList();
            $mapping = $session->get('k2martImportMapping', array());
            $K2Item = JTable::getInstance('K2Item', 'Table');
            $K2Item->title = $product->product_name;
            $K2Item->alias = $product->product_name;

            if (count($categories) && isset($mapping['categories'][$categories[0]->virtuemart_category_id]))
            {
                $K2Item->catid = $mapping['categories'][$categories[0]->virtuemart_category_id];

            }
            else
            {
                $K2Item->catid = $session->get('k2martImportParent');
            }

            $K2Item->trash = 0;
            $K2Item->published = $product->published;
            $K2Item->introtext = $product->product_s_desc;
            $K2Item->fulltext = $product->product_desc;
            $K2Item->created = $date->toMySQL();
            $K2Item->created_by = $user->id;
            $K2Item->access = 1;
            $K2Item->ordering = $K2Item->getNextOrder("catid = ".$K2Item->catid);
            $K2Item->hits = $product->hits;
            $K2Item->metadesc = $product->metadesc;
            $K2Item->metakey = $product->metakey;
            $K2Item->params = $itemParams;
            $K2Item->language = $lang;
            $K2Item->check();
            $K2Item->store();

            $vmMediaModel = JModel::getInstance('Media', 'VirtuemartModel');
            $media = $vmMediaModel->getFiles(true, false, $product->virtuemart_product_id, null);
            if (count($media) && JRequest::getBool('proccessImages'))
            {
                $image = realpath(JPATH_SITE.DS.$media[0]->file_url);
                if (JFile::exists($image))
                {
                    JLoader::register('Upload', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'class.upload.php');
                    $handle = new Upload($image);
                    $handle->allowed = array('image/*');

                    //Original image
                    $savepath = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'src';
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = 100;
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = md5("Image".$K2Item->id);
                    $handle->Process($savepath);

                    $filename = $handle->file_dst_name_body;
                    $savepath = JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache';

                    //XLarge image
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = $params->get('imagesQuality');
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = $filename.'_XL';
                    $handle->image_x = $params->get('itemImageXL', '800');
                    $handle->Process($savepath);

                    //Large image
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = $params->get('imagesQuality');
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = $filename.'_L';
                    $handle->image_x = $params->get('itemImageL', '600');
                    $handle->Process($savepath);

                    //Medium image
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = $params->get('imagesQuality');
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = $filename.'_M';
                    $handle->image_x = $params->get('itemImageM', '400');
                    $handle->Process($savepath);

                    //Small image
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = $params->get('imagesQuality');
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = $filename.'_S';
                    $handle->image_x = $params->get('itemImageS', '200');
                    $handle->Process($savepath);

                    //XSmall image
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = $params->get('imagesQuality');
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = $filename.'_XS';
                    $handle->image_x = $params->get('itemImageXS', '100');
                    $handle->Process($savepath);

                    //Generic image
                    $handle->image_resize = true;
                    $handle->image_ratio_y = true;
                    $handle->image_convert = 'jpg';
                    $handle->jpeg_quality = $params->get('imagesQuality');
                    $handle->file_auto_rename = false;
                    $handle->file_overwrite = true;
                    $handle->file_new_name_body = $filename.'_Generic';
                    $imageWidth = $params->get('itemImageGeneric', '300');
                    $handle->image_x = $imageWidth;
                    $handle->Process($savepath);
                }
            }

            // Tag the item
            foreach ($categories as $category)
            {
                if (isset($mapping['tags'][$category->virtuemart_category_id]))
                {
                    $db->setQuery("INSERT INTO #__k2_tags_xref (`id`, `tagID`, `itemID`) VALUES (NULL, {$mapping['tags'][$category->virtuemart_category_id]}, {$K2Item->id})");
                    $db->query();
                }
            }

            //Insert K2mart reference
            $query = "INSERT IGNORE INTO #__k2mart (`baseID`, `referenceID`) VALUES ({$K2Item->id}, {$product->virtuemart_product_id})";
            $db->setQuery($query);
            $db->query();

            // Update the response object
            $response->id = $product->virtuemart_product_id;
            $response->message = JText::_('K2MART_IMPORTED_PRODUCT').': '.JFilterOutput::cleanText($product->product_name);

        }

        return $response;
    }

}
