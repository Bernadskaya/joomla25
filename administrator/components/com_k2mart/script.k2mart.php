<?php
/**
 * @version		$Id: script.k2mart.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author    	JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class Com_K2martInstallerScript
{
	function update($type)
	{
		$db = JFactory::getDBO();
		$keys = $db->getTableKeys('#__k2mart');
		foreach ($keys as $key)
		{
			if ($key->Key_name == 'referenceID' && $key->Non_unique == '0')
			{
				$db->setQuery('ALTER TABLE `#__k2mart` DROP INDEX `referenceID`, ADD INDEX `referenceID` (`referenceID`)');
				$db->query();
			}
		}
	}

}
