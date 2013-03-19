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

JLoader::register('K2Plugin', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2'.DS.'lib'.DS.'k2plugin.php');

class plgK2K2mart extends K2Plugin
{

    function plgK2K2mart(&$subject, $config)
    {

        //Check if Virtuemart is installed
        jimport('joomla.filesystem.file');
        if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.php'))
        {
            return false;
        }

        //Load required classes
        jimport('joomla.application.component.model');
        require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
        $this->vmConfig = VmConfig::loadConfig();
        JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'models');
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'virtuemart'.DS.'tables');

        //Load language files
        JPlugin::loadLanguage('plg_k2_k2mart', JPATH_ADMINISTRATOR);
        JPlugin::loadLanguage('com_virtuemart');

        //Class variables
        $this->pluginName = 'k2mart';
        $this->pluginNameHumanReadable = '<img alt="K2mart" src="'.JURI::base(true).'/modules/mod_k2mart/tmpl/images/k2mart_logo_75x18.png" />';

        //Constructor call
        parent::__construct($subject, $config);
    }

    function onAfterK2Save(&$item, $isNew)
    {

        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();

        //Bind Variables
        $vars = JRequest::get('post');
        $productID = JRequest::getInt('virtuemart_product_id');
        $vars['product_name'] = $item->title;
        $vars['slug'] = $item->alias;
        $vars['product_special'] = $item->featured;
        $vars['product_s_desc'] = $item->introtext;
        $vars['product_desc'] = $item->fulltext;
        $vars['metarobot'] = $vars['meta']['robots'];
        $vars['metaauthor'] = $vars['meta']['author'];

        //Handle parent
        if ($vars['product_parent_id'])
        {
            $db->setQuery("SELECT referenceID FROM #__k2mart WHERE baseID = {$vars['product_parent_id']}");
            $vars['product_parent_id'] = $db->loadResult();
        }

        //Sync image
        if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XL.jpg'))
        {
            $main = 'media/k2/items/cache/'.md5("Image".$item->id).'_XL.jpg';
            $thumbnail = 'media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';
            $fileTitle = md5("Image".$item->id);
            if (isset($vars['active_media_id']))
            {
                $vars['media_action'] = '0';
                $vars['file_title'] = $fileTitle;
                $vars['file_url'] = $main;
                $vars['file_url_thumb'] = $thumbnail;
            }
            else
            {
                JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'tables');
                $media = JTable::getInstance('Medias', 'Table');
                $media->file_title = $fileTitle;
                $media->file_url = $main;
                $media->file_url_thumb = $thumbnail;
                $media->file_mimetype = 'image/jpeg';
                $media->file_type = 'product';
                $media->published = 1;
                $media->file_is_product_image = 1;
                $media->virtuemart_vendor_id = 1;
                $media->store();
                $virtuemart_media_id = $media->virtuemart_media_id;
            }

        }

        //Handle assignment
        if (JRequest::getBool('k2martAssignFlag'))
        {
            $query = "DELETE FROM #__k2mart WHERE baseID = {$item->id}";
            $db->setQuery($query);
            $db->query();
            $query = "INSERT INTO #__k2mart VALUES ({$item->id}, {$productID}) ON DUPLICATE KEY UPDATE referenceID=".$productID;
            $db->setQuery($query);
            $db->query();
            return;
        }

        //Handle unassignment
        if (JRequest::getBool('k2martUnassignFlag'))
        {
            $query = "DELETE FROM #__k2mart WHERE baseID = {$item->id}";
            $db->setQuery($query);
            $db->query();
            return;
        }

        //Handle remove
        if (JRequest::getBool('k2martDeleteFlag'))
        {
            if ($productID)
            {
                $query = "DELETE FROM #__k2mart WHERE referenceID = {$productID} AND baseID = {$item->id}";
                $db->setQuery($query);
                $db->query();
                $model = JModel::getInstance('Product', 'VirtuemartModel');
                $model->remove(array($productID));
            }
            return;
        }

        //Handle form
        if (JRequest::getVar('product_sku', NULL))
        {
            $model = JModel::getInstance('Product', 'VirtuemartModel');
            $productID = $model->store($vars);
            if (isset($virtuemart_media_id) && $virtuemart_media_id)
            {
                $query = "INSERT INTO #__virtuemart_product_medias VALUES (NULL, {$productID}, {$virtuemart_media_id}, 1)";
                $db->setQuery($query);
                $db->query();
            }
            $query = "INSERT IGNORE INTO #__k2mart VALUES ({$item->id}, {$productID})";
            $db->setQuery($query);
            $db->query();

            return;
        }

    }

    //@TODO Add digital products support. Still not complete in Virtuemart 2.

    function onK2AfterDisplayContent(&$item, &$params, $limitstart)
    {

        $mainframe = JFactory::getApplication();

        //Get params
        $this->params = JComponentHelper::getParams('com_k2mart');

        //Get application variables
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');

        //Define the layout
        if ($option == 'com_k2' && $view == 'item')
        {
            $layout = 'item';
        }
        else
        {
            $layout = 'listing';
        }
        if (array_key_exists('moduleclass_sfx', $params->toArray()))
        {
            $layout = 'module';
        }

        //Check for disabled layout
        if ($this->params->get($layout.'LayoutDisabled'))
        {
            return;
        }

        //Get product ID
        $db = JFactory::getDBO();
        $query = "SELECT referenceID FROM #__k2mart WHERE baseID = ".$item->id;
        $db->setQuery($query, 0, 1);
        $productID = $db->loadResult();

        if (is_null($productID))
        {
            return;
        }

        //Check for non-registered users
        $user = JFactory::getUser();
        if ($this->params->get('membersOnlyCart') && $user->guest)
        {
            $this->params->set($layout.'ProductAddToCart', 0);
        }

        //Set the virtuemart_product_id variable
        JRequest::setVar('virtuemart_product_id', $productID);

        //Get product
        $model = VmModel::getModel('product');
        $product = $model->getProduct($productID);

        //If product is unpublished or null return
        if (!$product || is_null($product) || !$product->published)
        {
            return;
        }

        //Rename some variables for ease of usage
        $product->id = $product->virtuemart_product_id;
        $product->name = $product->product_name;
        $product->sku = $product->product_sku;
        $product->url = $product->product_url;
        $product->manufacturerLink = '';
        $product->unit = $product->product_unit;
        $product->stock = $product->product_in_stock;
        $product->length = $product->product_length;
        $product->width = $product->product_width;
        $product->height = $product->product_height;
        $product->weight = $product->product_weight;
        $product->dimensionUnit = $product->product_lwh_uom;
        $product->weightUnit = $product->product_weight_uom;
        $product->availableDate = $product->product_available_date;
        $product->shipping = $product->product_availability;
        $product->catid = $product->virtuemart_category_id;
        $product->customFields = isset($product->customfields) ? $product->customfields : array();
        $product->customFieldsCart = isset($product->customfieldsCart) ? $product->customfieldsCart : array();
        $product->minimumOrder = (isset($product->min_order_level) && (int)$product->min_order_level > 0) ? $product->min_order_level : '1';
        $product->askQuestionLink = JRoute::_('index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id='.$product->id.'&virtuemart_category_id='.$product->catid.'&tmpl=component');
        $product->reviewFormAction = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id='.$product->id.'&virtuemart_category_id='.$product->catid);

        //Add to cart button
        $product->notifyLink = false;
        $product->addToCartButtonLabel = JText::_('K2MART_ADD_TO_CART');
        $product->addToCartButtonClass = 'addtocart-button';
        $product->addToCartButtonName = 'addtocart';
        $stockHandle = VmConfig::get('stockhandle', 'none');
        if ($stockHandle == 'disableadd' && ($product->product_in_stock - $product->product_ordered) < 1)
        {
            $product->notifyLink = JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=notify&virtuemart_product_id='.$product->id);
            $product->addToCartButtonLabel = JText::_('K2MART_NOTIFY_ME');
            $product->addToCartButtonClass = 'notify-button';
            $product->addToCartButtonName = 'notifycustomer';
        }

        //Get Vendor
        if ($this->params->get($layout.'ProductVendor'))
        {
            $model = JModel::getInstance('Vendor', 'VirtuemartModel');
            $model->setId($product->virtuemart_vendor_id);
            $vendor = $model->getVendor();
            $product->vendorID = $vendor->virtuemart_vendor_id;
            $product->vendorName = $vendor->vendor_name;
        }

        //Get manufacturer
        if ($this->params->get($layout.'ProductManufacturer'))
        {
            $db->setQuery("SELECT virtuemart_manufacturer_id FROM #__virtuemart_product_manufacturers WHERE virtuemart_product_id = ".$productID);
            $product->virtuemart_manufacturer_id = $db->loadResult();
            if ($product->virtuemart_manufacturer_id)
            {
                $model = JModel::getInstance('Manufacturer', 'VirtuemartModel');
                $model->setId($product->virtuemart_manufacturer_id);
                $manufacturer = $model->getManufacturer();
                $product->manufacturerName = $manufacturer->mf_name;
                $product->manufacturerLink = JRoute::_('index.php?option=com_virtuemart&view=manufacturer&virtuemart_manufacturer_id='.$product->virtuemart_manufacturer_id.'&tmpl=component');
            }
        }

        //Get availability
        $product->availability = '';
        if (($product->product_in_stock - $product->product_ordered) > 0)
        {
            $product->availability = $product->stock.' '.JText::_('K2MART_ITEMS_AVAILABLE');
        }
        else
        {
            if ($stockHandle == 'risetime' && VmConfig::get('rised_availability'))
            {
                $product->availability = VmConfig::get('rised_availability');
            }
            else
            {
                $date = JFactory::getDate();
                $now = $date->toMySQL();

                if ($product->availableDate > $now)
                {
                    $product->availability = JText::_('K2MART_AVAILABLE_AGAIN_ON').' '.JHTML::_('date', $product->availableDate, JText::_('DATE_FORMAT_LC4'));
                }
                else
                {
                    $product->availability = JText::_('K2MART_CURRENTLY_NOT_AVAILABLE').' ';
                }
            }
        }

        //Get shipping info
        if (!empty($product->shipping) && JFile::exists(realpath(VmConfig::get('assets_general_path').'images/availability/'.$product->shipping)))
        {
            $product->shipping = JHTML::image(JURI::root().VmConfig::get('assets_general_path').'images/availability/'.$product->shipping, $product->shipping, array('class' => 'availability'));
        }

        //Get reviews
        if ($this->params->get($layout.'ProductReviews'))
        {
            $model = JModel::getInstance('Ratings', 'VirtuemartModel');
            $product->allowReview = $model->allowReview($product->virtuemart_product_id);
            $product->showReview = $model->showReview($product->virtuemart_product_id);
            if ($product->showReview)
            {
                $product->userReview = $model->getReviewByProduct($product->virtuemart_product_id);
                $product->reviews = $model->getReviews($product->virtuemart_product_id);
            }
            if ($product->allowReview)
            {
                $script = "
				function check_reviewform() {
					var form = document.getElementById('reviewform');
					var ausgewaehlt = false;
					if (form.comment.value.length < ".VmConfig::get('reviews_minimum_comment_length', 100).") {
						alert('".addslashes(JText::sprintf('COM_VIRTUEMART_REVIEW_ERR_COMMENT1_JS', VmConfig::get('reviews_minimum_comment_length', 100)))."');
						return false;
					}
					else if (form.comment.value.length > ".VmConfig::get('reviews_maximum_comment_length', 2000).") {
						alert('".addslashes(JText::sprintf('COM_VIRTUEMART_REVIEW_ERR_COMMENT2_JS', VmConfig::get('reviews_maximum_comment_length', 2000)))."');
						return false;
					}
					else {
						return true;
					}
				}
				function refresh_counter() {
					var form = document.getElementById('reviewform');
					form.counter.value= form.comment.value.length;
				}";
                $document = JFactory::getDocument();
                $document->addScriptDeclaration($script);
            }
        }

        //Get rating
        if ($this->params->get($layout.'ProductRating'))
        {
            $model = JModel::getInstance('Ratings', 'VirtuemartModel');
            $product->showRating = $model->showRating($product->virtuemart_product_id);
            if ($product->showRating)
            {
                $product->rating = $model->getRatingByProduct($product->virtuemart_product_id);
                $maxRating = VmConfig::get('vm_maximum_rating_scale', 5);
                $product->rating = empty($product->rating) ? '0/0' : round($product->rating->rating, 2).'/'.$maxRating;
            }
            $product->allowRating = $model->allowRating($product->virtuemart_product_id);
        }

        // Prepare reviews
        if ($this->params->get($layout.'ProductReviews') && ($product->allowRating || $product->showReview))
        {
            $maxRating = VmConfig::get('vm_maximum_rating_scale', 5);
            for ($num = 0; $num <= $maxRating; $num++)
            {
                $title = (JText::_("COM_VIRTUEMART_RATING_TITLE").$num.'/'.$maxRating);
                $stars[] = '<span class="vmicon vm2-stars'.$num.'" title="'.$title.'"></span>';
            }
            $reviewEditable = true;
            if ($product->reviews)
            {
                foreach ($product->reviews as $key => $review)
                {
                    if ($key % 2 == 0)
                    {
                        $review->class = 'normal';
                    }
                    else
                    {
                        $review->class = 'highlight';
                    }
                    if ($review->created_by == $user->id && !$review->review_editable)
                    {
                        $reviewEditable = false;
                    }
                }
            }

        }

        // Prepare product custom fields
        if (is_array($product->customFields))
        {
            $custom_title = null;
            foreach ($product->customFields as &$customField)
            {
                $tmp = $customField->custom_title;
                if ($customField->custom_title == $custom_title)
                {
                    $customField->custom_title = false;
                }
                $custom_title = $tmp;
            }
        }

        // Fetch the main and the extended prices
        require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'permissions.php');
        require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'currencydisplay.php');
        $currency = CurrencyDisplay::getInstance();
        $product->mainPrice = $currency->createPriceDiv('salesPrice', '', $product->prices);
        $product->extendedPrice = '';
        if (Permissions::getInstance()->check('admin'))
        {
            $product->extendedPrice .= $currency->createPriceDiv('basePrice', 'COM_VIRTUEMART_PRODUCT_BASEPRICE', $product->prices);
            $product->extendedPrice .= $currency->createPriceDiv('basePriceVariant', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_VARIANT', $product->prices);
        }
        $product->extendedPrice .= $currency->createPriceDiv('variantModification', 'COM_VIRTUEMART_PRODUCT_VARIANT_MOD', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('basePriceWithTax', 'COM_VIRTUEMART_PRODUCT_BASEPRICE_WITHTAX', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('discountedPriceWithoutTax', 'COM_VIRTUEMART_PRODUCT_DISCOUNTED_PRICE', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('salesPriceWithDiscount', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITH_DISCOUNT', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('salesPrice', 'COM_VIRTUEMART_PRODUCT_SALESPRICE', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('priceWithoutTax', 'COM_VIRTUEMART_PRODUCT_SALESPRICE_WITHOUT_TAX', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('discountAmount', 'COM_VIRTUEMART_PRODUCT_DISCOUNT_AMOUNT', $product->prices);
        $product->extendedPrice .= $currency->createPriceDiv('taxAmount', 'COM_VIRTUEMART_PRODUCT_TAX_AMOUNT', $product->prices);

        // Assign the product object to the K2 item so it is available in K2 layouts
        $item->product = $product;

        // Render
        ob_start();
        include $this->getLayoutPath($layout);
        $contents = ob_get_clean();
        return $contents;

    }

    function onK2BeforeAssignFilters(&$filters)
    {
        if ($this->getAdminProcessFlag())
        {
            $mainframe = JFactory::getApplication();
            $option = JRequest::getCmd('option');
            $view = JRequest::getCmd('view');
            $manufacturer = $mainframe->getUserStateFromRequest($option.$view.'k2martManufacturer', 'k2martManufacturer', 0, 'int');
            $relation = $mainframe->getUserStateFromRequest($option.$view.'k2martRelation', 'k2martRelation', -1, 'string');
            require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'helpers'.DS.'html.php');
            $filters['k2martManufacturer'] = K2martHTMLHelper::getManufacturerFilter('k2martManufacturer', $manufacturer, '', true);
            $filters['k2martRelation'] = K2martHTMLHelper::getRelationFilter('k2martRelation', $relation, '', true);
        }
    }

    function onK2BeforeAssignColumns(&$columns)
    {
        if ($this->getAdminProcessFlag())
        {
            $column = new JObject;
            $column->property = 'price';
            $column->label = 'K2MART_PRICE';
            $column->class = 'k2martPrice';
            $columns[] = $column;
            $column = new JObject;
            $column->property = 'sku';
            $column->label = 'K2MART_SKU';
            $column->class = 'k2martSku';
            $columns[] = $column;
            $column = new JObject;
            $column->property = 'manufacturer';
            $column->label = 'K2MART_MANUFACTURER';
            $column->class = 'k2martManufacturer';
            $columns[] = $column;
            $document = JFactory::getDocument();
            $document->addStyleDeclaration('.k2martPrice {text-align:right;} .k2martSku, .k2martManufacturer {text-align:center;}');
        }
    }

    function onK2BeforeSetQuery(&$query)
    {

        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');

        if ($mainframe->isAdmin())
        {
            if ($this->getAdminProcessFlag())
            {
                $manufacturer = $mainframe->getUserStateFromRequest($option.$view.'k2martManufacturer', 'k2martManufacturer', 0, 'int');
                $relation = $mainframe->getUserStateFromRequest($option.$view.'k2martRelation', 'k2martRelation', -1, 'string');

                // Modify the select statement
                $parts = explode(' FROM ', $query);
                $parts[0] .= " ,product.product_sku AS sku, 
				prices.product_price AS price,
				prices.product_currency AS currency,
				manufacturers.virtuemart_manufacturer_id,
				manufacturers.mf_name AS manufacturer";

                $query = implode(' FROM ', $parts);

                $parts = explode('WHERE', $query);
                $parts[0] .= " RIGHT JOIN #__k2mart AS k2mart ON k2mart.baseID = i.id 
				LEFT JOIN #__virtuemart_products AS product ON product.virtuemart_product_id = k2mart.referenceID 
				LEFT JOIN #__virtuemart_product_prices AS prices ON  product.virtuemart_product_id = prices.virtuemart_product_id 
				LEFT JOIN #__virtuemart_product_manufacturers AS manufacturersXref ON  product.virtuemart_product_id = manufacturersXref.virtuemart_product_id 
				LEFT JOIN #__virtuemart_manufacturers_".VMLANG." AS manufacturers ON manufacturersXref.virtuemart_manufacturer_id = manufacturers.virtuemart_manufacturer_id";
                $sql = "";
                $conditions = array();
                if ($relation == 0)
                {
                    $conditions[] = "product.product_parent_id = ".$relation;
                }
                elseif ($relation > 0)
                {
                    $conditions[] = "product.product_parent_id != 0";
                }
                if ($manufacturer)
                {
                    $conditions[] = "manufacturersXref.virtuemart_manufacturer_id = ".$manufacturer;
                }
                $sql = implode(' AND ', $conditions);
                if ($sql)
                {
                    $sql .= ' AND ';
                }
                $parts[1] = $sql.$parts[1];
                $query = implode(' WHERE ', $parts);
            }

        }
        else
        {

            if ($this->getProcessFlag())
            {

                $ordering = JRequest::getVar('k2martOrdering', 'name');
                $orderingDir = JRequest::getVar('k2martOrderingDir', 'asc');
                $categories = JRequest::getVar('k2martCategories');
                JArrayHelper::toInteger($categories);
                $manufacturers = JRequest::getVar('k2martManufacturers');
                JArrayHelper::toInteger($manufacturers);

                $parts = explode('WHERE', $query);
                $parts[0] .= " RIGHT JOIN #__k2mart AS k2mart ON k2mart.baseID = i.id
				RIGHT JOIN #__virtuemart_products AS product ON product.virtuemart_product_id = k2mart.referenceID 
				LEFT JOIN #__virtuemart_product_manufacturers AS manufacturersXref ON  product.virtuemart_product_id = manufacturersXref.virtuemart_product_id
				LEFT JOIN #__virtuemart_product_prices AS prices ON  product.virtuemart_product_id = prices.virtuemart_product_id ";

                $sql = "";
                $conditions = array();
                if ($manufacturers)
                {
                    $conditions[] = "manufacturersXref.virtuemart_manufacturer_id IN (".implode(',', $manufacturers).")";
                }

                // Check of Virtuemart is setup to hide out of stock products. If so hide them.
                $stockHandle = VmConfig::get('stockhandle', 'none');
                if ($stockHandle == 'disableit')
                {
                    $conditions[] = "(product.product_in_stock - product.product_ordered) > 0";
                }

                // Detect the query category filter
                $task = JRequest::getCmd('task');
                if ($task == 'category')
                {
                    $regex = "#c.id IN \((.*?)\)#s";

                }
                elseif ($task == '')
                {
                    $regex = "#AND i.catid IN \((.*?)\)#s";
                }
                preg_match($regex, $query, $matches);
                if (isset($matches[1]))
                {
                    $catids = explode(',', $matches[1]);
                    JArrayHelper::toInteger($catids);
                }
                else
                {
                    $catids = array();
                }
                $this->categories = $catids;
                if ($categories)
                {
                    if ($task == 'category')
                    {
                        $parts[1] = JString::str_ireplace('c.id IN ('.implode(',', $catids).')', 'c.id IN ('.implode(',', $categories).')', $parts[1]);
                    }
                    elseif ($task == '')
                    {
                        $parts[1] = JString::str_ireplace('i.catid IN ('.implode(',', $catids).')', 'i.catid IN ('.implode(',', $categories).')', $parts[1]);
                    }
                }
                $sql = implode(' AND ', $conditions);
                if ($sql)
                {
                    $sql .= ' AND ';
                }
                $parts[1] = $sql.$parts[1];
                $query = implode(' WHERE ', $parts);
                $parts = explode('ORDER BY', $query);

                switch($ordering)
                {
                    case 'name' :
                        $sql = 'i.title';
                        break;
                    case 'sku' :
                        $sql = 'product.product_sku';
                        break;
                    case 'price' :
                        $sql = 'prices.product_price';
                        break;
                    case 'sales' :
                        $sql = 'product.product_sales';
                        break;
                    case 'hits' :
                        $sql = 'i.hits';
                        break;
                    case 'date' :
                        $sql = 'i.publish_up';
                        break;
                }
                switch($orderingDir)
                {
                    case 'asc' :
                        $sql .= ' ASC';
                        break;
                    case 'desc' :
                        $sql .= ' DESC';
                        break;
                }
                if (isset($sql))
                {
                    $parts[1] = $sql;
                }
                $query = implode(' ORDER BY ', $parts);
            }

        }

    }

    function onK2BeforeViewDisplay()
    {
        if ($this->getProcessFlag())
        {
            $mainframe = JFactory::getApplication();
            $params = JComponentHelper::getParams('com_k2mart');
            $filters = array();
            $ordering = JRequest::getVar('k2martOrdering', 'name');
            $orderingDir = JRequest::getVar('k2martOrderingDir', 'asc');
            $categories = JRequest::getVar('k2martCategories');
            $manufacturers = JRequest::getVar('k2martManufacturers');

            if ($params->get('sortingFilter'))
            {
                $options = array();
                $options[] = JHTML::_('select.option', 'name', JText::_('K2MART_NAME'));
                $options[] = JHTML::_('select.option', 'sku', JText::_('K2MART_SKU'));
                $options[] = JHTML::_('select.option', 'price', JText::_('K2MART_PRICE'));
                $options[] = JHTML::_('select.option', 'sales', JText::_('K2MART_SALES'));
                $options[] = JHTML::_('select.option', 'hits', JText::_('K2MART_HITS'));
                $options[] = JHTML::_('select.option', 'date', JText::_('K2MART_DATE'));
                $filters['k2martOrdering'] = JHTML::_('select.genericlist', $options, 'k2martOrdering', 'class="k2martFilter"', 'value', 'text', $ordering);
                $options = array();
                $options[] = JHTML::_('select.option', 'asc', JText::_('K2MART_ASCENDING'));
                $options[] = JHTML::_('select.option', 'desc', JText::_('K2MART_DESCENDING'));
                $filters['k2martOrderingDir'] = JHTML::_('select.genericlist', $options, 'k2martOrderingDir', 'class="k2martFilter"', 'value', 'text', $orderingDir);
            }
            else
            {
                $filters['k2martOrdering'] = false;
            }

            if ($params->get('manufacturersFilter'))
            {
                require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'helpers'.DS.'html.php');
                $filters['k2martManufacturers'] = K2martHTMLHelper::getManufacturerFilter('k2martManufacturers[]', $manufacturers, 'class="k2martFilter" multiple="multiple" data-placeholder="'.JText::_('K2MART_CLICK_TO_SELECT_MANUFACTURERS').'"');
            }
            else
            {
                $filters['k2martManufacturers'] = false;
            }

            if ($params->get('categoriesFilter') && count($this->categories) > 1)
            {
                require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_k2mart'.DS.'helpers'.DS.'html.php');
                $filters['k2martCategories'] = K2martHTMLHelper::getCategoryFilter('k2martCategories[]', $categories, 'class="k2martFilter" multiple="multiple" data-placeholder="'.JText::_('K2MART_CLICK_TO_SELECT_CATEGORIES').'"', $this->categories);
            }
            else
            {
                $filters['k2martCategories'] = false;
            }

            //Render
            ob_start();
            include $this->getLayoutPath('filters');
            $contents = ob_get_clean();
            echo $contents;
        }
    }

    function getAdminProcessFlag()
    {
        if (isset($this->adminProcessFlag))
        {
            return $this->adminProcessFlag;
        }
        $this->adminProcessFlag = false;
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        $params = JComponentHelper::getParams('com_k2mart');
        $root = $params->get('catalogRoot');
        if ($mainframe->isAdmin() && $option == 'com_k2' && ($view == 'items' || $view == '') && $root)
        {
            $category = $mainframe->getUserStateFromRequest('com_k2itemsfilter_category', 'filter_category', 0, 'int');
            $ordering = $mainframe->getUserStateFromRequest('com_k2itemsfilter_order', 'filter_order', 'i.id', 'cmd');
            JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models');
            $model = JModel::getInstance('itemlist', 'K2Model');
            $tree = $model->getCategoryTree($root);
            if (in_array($category, $tree))
            {
                $this->adminProcessFlag = true;
            }
        }
        return $this->adminProcessFlag;
    }

    function getProcessFlag()
    {
        if (isset($this->processFlag))
        {
            return $this->processFlag;
        }
        $this->processFlag = false;
        $view = JRequest::getCmd('view');
        $task = JRequest::getCmd('task');

        $params = JComponentHelper::getParams('com_k2mart');
        $root = $params->get('catalogRoot');

        if ($view == 'itemlist' && $task == 'category')
        {
            $categories = array();
            $categories[] = JRequest::getInt('id');
        }
        else if ($view == 'itemlist' && $task == '')
        {
            $menu = JSite::getMenu();
            $menuItem = $menu->getActive();
            if ($menuItem)
            {
                $categories = $menuItem->params->get('categories');
            }
        }
        if (isset($categories) && is_array($categories) && count($categories) && $root)
        {
            JArrayHelper::toInteger($categories);
            JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models');
            $model = JModel::getInstance('itemlist', 'K2Model');
            $tree = $model->getCategoryTree($root);
            $intersection = array_intersect($categories, $tree);
            if (!empty($intersection))
            {
                $this->processFlag = true;
            }
        }
        return $this->processFlag;
    }

    function getLayoutPath($layout)
    {
        jimport('joomla.filesystem.file');
        $mainframe = JFactory::getApplication();
        $params = $mainframe->getParams('com_k2mart');
        $document = JFactory::getDocument();
        //Load required head data
        $this->loadHeadData();
        // Load template style override
        $K2martTemplate = $params->get('template', 'template1');
        if (JFile::exists(JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'k2mart'.DS.$K2martTemplate.DS.'css'.DS.'style.css'))
        {
            $document->addStyleSheet(JURI::root(true).'/templates/'.$mainframe->getTemplate().'/html/k2mart/'.$K2martTemplate.'/css/style.css');
        }
        else
        {
            $document->addStyleSheet(JURI::root(true).'/plugins/k2/k2mart/tmpl/site/'.$K2martTemplate.'/css/style.css');
        }
        // Get the layout path
        if (JFile::exists(JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'k2mart'.DS.$K2martTemplate.DS.$layout.'.php'))
        {
            return JPATH_SITE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.'k2mart'.DS.$K2martTemplate.DS.$layout.'.php';
        }
        else
        {
            return JPATH_SITE.DS.'plugins'.DS.'k2'.DS.'k2mart'.DS.'tmpl'.DS.'site'.DS.$K2martTemplate.DS.$layout.'.php';
        }
    }

    function loadHeadData()
    {
        if (isset($this->headData))
        {
            return;
        }
        $mainframe = JFactory::getApplication();
        $document = JFactory::getDocument();
        $params = JComponentHelper::getParams('com_k2mart');
        $uri = JURI::getInstance();
        $query = $uri->getQuery(true);
        unset($query['k2martCategories']);
        unset($query['k2martManufacturers']);
        unset($query['k2martOrdering']);
        unset($query['k2martOrderingDir']);
        unset($query['start']);
        $uri->setQuery($query);
        $url = $uri->toString();
        if (JString::strpos($url, '?') === false)
        {
            $url .= '?';
        }
        else
        {
            $url .= '&';
        }
        $menu = $mainframe->getMenu();
        $frontPageFlag = ($menu->getActive() == $menu->getDefault());
        if ($document->getType() == 'html')
        {
            vmJsApi::jPrice();
            $document->addStyleSheet(JURI::root(true).'/administrator/components/com_k2mart/css/chosen.css');
            $ajaxFlag = $params->get('ajax') ? 'true' : 'false';
            $frontPageFlag = $frontPageFlag ? 'true' : 'false';
            $document->addScriptDeclaration('var K2martURL = "'.$url.'"; var K2martNoResultsText = "'.JText::_('K2MART_NO_RESULTS_MATCH', true).'"; var K2martAJAX = '.$ajaxFlag.'; var K2martFrontPage = '.$frontPageFlag.';');
            $document->addScript(JURI::root(true).'/administrator/components/com_k2mart/js/chosen.jquery.min.js');
            $document->addScript(JURI::root(true).'/plugins/k2/k2mart/includes/js/site.k2mart.js');
            $document->addCustomTag('
				<!--[if lte IE 6]>
				<style type="text/css">
					.k2martClearFix { height: 1%; }
				</style>
				<![endif]-->
				<!--[if IE 7]>
				<style type="text/css">
					.k2martClearFix { display:inline-block; }
				</style>
				<![endif]-->
			');
            $this->headData = true;
        }
    }

}
