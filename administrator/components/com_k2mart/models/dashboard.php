<?php
/**
 * @version		$Id: dashboard.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class K2martModelDashboard extends JModel
{

	function countK2Items()
	{
		jimport('joomla.filesystem.file');
		if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'k2.php'))
		{
			return JText::_('K2MART_K2_WAS_NOT_FOUND');
		}
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2_items";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	function countVmProducts()
	{
		jimport('joomla.filesystem.file');
		if (!JFile::exists(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.php'))
		{
			return JText::_('K2MART_VIRTUEMART_WAS_NOT_FOUND');
		}

		$db = $this->getDBO();
		$query = "SELECT COUNT(*) FROM #__virtuemart_products";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	function countK2martProducts()
	{
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(*) FROM #__k2mart";
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}

	function loadChartData()
	{

		$db = $this->getDBO();
		$type = JRequest::getCmd('type');

		switch ($type)
		{

			case 'sales' :
				jimport('joomla.utilities.date');
				$date = JFactory::getDate();
				$interval = JRequest::getInt('interval', '14');
				$today = $date->toFormat('%Y-%m-%d');
				$startDate = strtotime('-'.$interval.' day', strtotime($today));
				$startDate = new JDate($startDate);
				$query = "SELECT COUNT(virtuemart_order_id) AS sales, DATE(created_on) as `date` FROM #__virtuemart_orders WHERE created_on > ".$db->Quote($startDate->toMySQL())." GROUP BY `date` ORDER BY `date`";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$data = array();
				foreach ($rows as $row)
				{
					$data[$row->date] = (int)$row->sales;
				}
				$today = $date->toUnix();
				for ($time = $startDate->toUnix(); $time <= $today; $time += 86400)
				{
					$date = date('Y', $time).'-'.date('m', $time).'-'.date('d', $time);
					if (!array_key_exists($date, $data))
					{
						$data[$date] = 0;
					}
				}
				ksort($data);
				$startYear = $startDate->toFormat('%Y');
				$startMonth = $startDate->toFormat('%m') - 1;
				$startDay = $startDate->toFormat('%d');
				$script = "
                k2martSalesChartOptions.title.text = '".JText::_('K2MART_TOTAL_SALES', true)."';
                k2martSalesChartOptions.subtitle.text = '* ".JText::_('K2MART_CLICK_AND_DRAG_IN_THE_PLOT_AREA_TO_ZOOM_IN', true)."';
                k2martSalesChartOptions.yAxis.title.text = '".JText::_('K2MART_SALES', true)."';
                k2martSalesChartOptions.series[0].pointStart=Date.UTC(".$startYear.", ".$startMonth.", ".$startDay."); 
                k2martSalesChartOptions.series[0].data=[".implode(',', $data)."];
                ";
				break;

			case 'products' :
				$limit = JRequest::getInt('limit', '20');
				$query = "SELECT product.product_sales, productData.product_name FROM #__virtuemart_products AS product 
                LEFT JOIN #__virtuemart_products_".VMLANG." AS productData ON product.virtuemart_product_id = productData.virtuemart_product_id
                WHERE  product.product_sales > 0 ORDER BY product.product_sales DESC LIMIT 0, {$limit}";
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$data = array();
				$categories = array();
				foreach ($rows as $row)
				{
					$data[] = (int)$row->product_sales;
					$categories[] = "'".$row->product_name."'";
				}
				$script = "
                k2martProductsChartOptions.title.text = '".JText::_('K2MART_TOP_SELLING_PRODUCTS', true)."';
                k2martProductsChartOptions.yAxis.title.text = '".JText::_('K2MART_SALES', true)."';
                k2martProductsChartOptions.xAxis.categories =[".implode(',', $categories)."]; 
                k2martProductsChartOptions.series[0].data=[".implode(',', $data)."];
                ";
				break;
		}

		$script .= "k2martChartType = '{$type}';";
		echo $script;

	}

}
