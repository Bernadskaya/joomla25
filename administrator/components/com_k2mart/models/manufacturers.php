<?php
/**
 * @version		$Id: manufacturers.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class K2martModelManufacturers extends JModel
{

	function getData()
	{
		$db = $this->getDBO();
		$query = "SELECT manufacturerData.* FROM #__virtuemart_manufacturers_".VMLANG." AS manufacturerData 
		LEFT JOIN #__virtuemart_manufacturers AS manufacturer ON manufacturerData.virtuemart_manufacturer_id = manufacturer.virtuemart_manufacturer_id";
		if (!is_null($this->getState('published')))
		{
			$query .= " WHERE manufacturer.published = ".(int)$this->getState('published');
		}
		$query .= " ORDER BY ".$this->getState('ordering', 'mf_name')." ".$this->getState('orderingDir', 'ASC');
		$db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$rows = $db->loadObjectList();
		return $rows;
	}

}
