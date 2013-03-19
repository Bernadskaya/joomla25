<?php
/**
 * @version		$Id: virtuemart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

defined('_JEXEC') or die('Restricted access');

class JElementVirtuemart extends JElement
{

	var $_name = 'Virtuemart';

	function fetchElement($name, $value, &$node, $control_name)
	{

		//Load languages
		$language = JFactory::getLanguage();
		$language->load('plg_k2_k2mart', JPATH_ADMINISTRATOR);
		$language->load('com_virtuemart', JPATH_ADMINISTRATOR);

		//Get params
		$params = JComponentHelper::getParams('com_k2mart');
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();

		//Add scripts and styles
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/plugins/k2/k2mart/tmpl/admin/css/admin.style.css');
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_k2mart/css/chosen.css');
		$document->addScript(JURI::root(true).'/plugins/k2/k2mart/includes/js/admin.k2mart.js');
		$document->addScript(JURI::root(true).'/administrator/components/com_k2mart/js/chosen.jquery.min.js');
		$document->addScript(JURI::root(true).'/components/com_virtuemart/assets/js/fancybox/jquery.fancybox-1.3.4.js');
		$document->addScript(JURI::root(true).'/administrator/components/com_virtuemart/assets/js/vm2admin.js');
		$document->addScript(JURI::root(true).'/administrator/components/com_virtuemart/assets/js/jquery.coookie.js');

		//Get K2 Item
		$itemID = JRequest::getInt('cid');
		$productID = 0;
		if ($itemID)
		{
			$query = "SELECT referenceID FROM #__k2mart WHERE baseID = {$itemID}";
			$db->setQuery($query);
			$productID = $db->loadResult();
		}

		//Include Virtuemart classes
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'config.php');
		$config = VmConfig::loadConfig();
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'shopfunctions.php');
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'html.php');
		JModel::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'models');

		//Get product
		$model = JModel::getInstance('Product', 'VirtuemartModel');
		$product = $model->getProductSingle($productID, false);

		//Get product children
		$product->children = method_exists($model, 'getProductChilds') ? $model->getProductChilds($productID) : $model->getProductChildIds($productID);

		//Get product parent
		JModel::addIncludePath(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models');
		$K2Model = JModel::getInstance('Itemlist', 'K2Model');
		$tree = $K2Model->getCategoryTree($params->get('catalogRoot', 0));
		$query = "SELECT id AS value, title AS text FROM #__k2_items WHERE catid IN(".implode(',', $tree).")";
		$db->setQuery($query);
		$items = $db->loadObjectList();
		$option = new JObject;
		$option->value = 0;
		$option->text = JText::_('K2MART_NONE');
		array_unshift($items, $option);
		$parent = 0;
		if ($product->product_parent_id)
		{
			$query = "SELECT baseID FROM #__k2mart WHERE referenceID = {$product->product_parent_id}";
			$db->setQuery($query);
			$parent = $db->loadResult();
		}
		$lists['product_parent_id'] = JHTML::_('select.genericlist', $items, 'product_parent_id', 'class="inputbox"', 'value', 'text', $parent);

		//Get category tree
		if (isset($product->categories))
		{
			$lists['categories'] = ShopFunctions::categoryListTree($product->categories);
		}
		else
		{
			$lists['categories'] = ShopFunctions::categoryListTree();
		}

		//Get vendors
		if (Vmconfig::get('multix', 'none') !== 'none')
		{
			$lists['vendors'] = Shopfunctions::renderVendorList($product->virtuemart_vendor_id);
		}

		//Get images
		$model->addImages($product);
		if (is_array($product->images) && isset($product->images[0]))
		{
			$product->image = $product->images[0];
		}
		else
		{
			$product->image = new JObject();
			$product->image->virtuemart_media_id = 0;
			$product->image->file_title = '';
			$product->image->file_description = '';
			$product->image->file_meta = '';
			$product->image->file_url = '';
			$product->image->file_url_thumb = '';
			$product->image->file_title = '';
		}

		//Get manufacturers
		$model = JModel::getInstance('Manufacturer', 'VirtuemartModel');
		$manufacturers = $model->getManufacturerDropdown($product->virtuemart_manufacturer_id);
		if (count($manufacturers) > 0)
		{
			$lists['manufacturers'] = JHTML::_('select.genericlist', $manufacturers, 'virtuemart_manufacturer_id', 'class="inputbox"', 'value', 'text', $product->virtuemart_manufacturer_id);
		}

		//Get shopper groups
		if (isset($product->shoppergroups))
		{
			$lists['shopperGroups'] = ShopFunctions::renderShopperGroupList($product->shoppergroups, true);
		}

		//Get product price
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'calculationh.php');
		$calculator = calculationHelper::getInstance();
		$product->prices = $calculator->getProductPrices($product);

		// Get product price override options
		$options = array(
			0 => 'Disabled',
			1 => 'Overwrite final',
			-1 => 'Overwrite price to tax'
		);
		$lists['price_override_options'] = VmHtml::radioList('override', $product->override, $options);

		// Get tax rules
		$dbTax = '';
		foreach ($calculator->rules['DBTax'] as $rule)
		{
			$dbTax .= $rule['calc_name'].'<br />';
		}
		$dbTaxRules = $dbTax;

		$tax = JText::_('COM_VIRTUEMART_TAX_EFFECTING');
		foreach ($calculator->rules['Tax'] as $rule)
		{
			$tax .= $rule['calc_name'].'<br />';
		}
		$taxRules = $tax;

		$daTax = '';
		foreach ($calculator->rules['DATax'] as $rule)
		{
			$daTax .= $rule['calc_name'].'<br />';
		}
		$daTaxRules = $daTax;

		// Removed in VM 2.0.4
		//$override = $calculator->override;
		//$product_override_price = $calculator->product_override_price;

		if (!isset($product->product_tax_id))
		{
			$product->product_tax_id = 0;
		}
		$lists['taxrates'] = ShopFunctions::renderTaxList($product->product_tax_id, 'product_tax_id');

		// Discounts
		if (!isset($product->product_discount_id))
		{
			$product->product_discount_id = 0;
		}
		require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart'.DS.'models'.DS.'calc.php');
		$discounts = VirtueMartModelCalc::getDiscounts();
		$discountrates = array();
		$discountrates[] = JHTML::_('select.option', '-1', JText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_NONE'), 'product_discount_id');
		$discountrates[] = JHTML::_('select.option', '0', JText::_('COM_VIRTUEMART_PRODUCT_DISCOUNT_NO_SPECIAL'), 'product_discount_id');
		foreach ($discounts as $discount)
		{
			$discountrates[] = JHTML::_('select.option', $discount->virtuemart_calc_id, $discount->calc_name, 'product_discount_id');
		}
		$lists['discounts'] = JHTML::_('select.genericlist', $discountrates, 'product_discount_id', '', 'product_discount_id', 'text', $product->product_discount_id);

		//Define Virtuemart image path
		if (is_Dir(VmConfig::get('vmtemplate').DS.'images'.DS.'availability'.DS))
		{
			$imagePath = VmConfig::get('vmtemplate').'/images/availability/';
		}
		else
		{
			$imagePath = '/components/com_virtuemart/assets/images/availability/';
		}

		// Get currencies
		$model = JModel::getInstance('Currency', 'VirtuemartModel');
		$vendorModel = JModel::getInstance('Vendor', 'VirtuemartModel');
		$vendorModel->setId(1);
		$vendor = $vendorModel->getVendor();
		if (empty($product->product_currency))
		{
			$product->product_currency = $vendor->vendor_currency;
		}
		$lists['currencies'] = JHTML::_('select.genericlist', $model->getCurrencies(), 'product_currency', '', 'virtuemart_currency_id', 'currency_name', $product->product_currency);
		$currency = $model->getCurrency($product->product_currency);
		$productCurrency = $currency->currency_symbol;
		$currency = $model->getCurrency($vendor->vendor_currency);
		$vendorCurrency = $currency->currency_symbol;

		//Get dimensions
		$lists['product_weight_uom'] = ShopFunctions::renderWeightUnitList('product_weight_uom', !isset($product->product_weight_uom) ? $config->get('weight_unit_default') : $product->product_weight_uom);
		$lists['product_lwh_uom'] = ShopFunctions::renderLWHUnitList('product_lwh_uom', !isset($product->product_lwh_uom) ? $config->get('lwh_unit_default') : $product->product_lwh_uom);

		//Get custom fields
		$model = JModel::getInstance('CustomFields', 'VirtuemartModel');
		$fieldTypes = $model->getField_types();
		$customsList = JHTML::_('select.genericlist', $model->getCustomsList(), 'customlist');
		if (!isset($product->customfields))
		{
			$product->customfields = array();
		}

		//Set some script variables
		$document->addScriptDeclaration('var k2martVmImagePath="'.JURI::root(true).$imagePath.'"; var tip_image="'.JURI::root(true).'/components/com_virtuemart/assets/js/images/vtip_arrow.png"; var k2martVmCustomFieldsNum = "'.count($product->customfields).'";');

		//Edit icon
		$application = JFactory::getApplication('admin');
		$editIcon = JURI::root(true).'/administrator/templates/'.$application->getTemplate().'/images/menu/icon-16-edit.png';

		//Output
		ob_start();
		include JPATH_SITE.DS.'plugins'.DS.'k2'.DS.'k2mart'.DS.'tmpl'.DS.'admin'.DS.'form.php';
		$contents = ob_get_clean();
		return $contents;
	}

	function fetchTooltip($label, $description, &$xmlElement, $control_name = '', $name = '')
	{
		return NULL;
	}

}
