<?php
/**
 * @version		$Id: view.html.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class K2martViewInfo extends JView
{

	function display($tpl = null)
	{
		jimport('joomla.filesystem.file');
		$db = JFactory::getDBO();
		$db_version = $db->getVersion();
		$php_version = phpversion();
		if (isset($_SERVER['SERVER_SOFTWARE']))
		{
			$server = $_SERVER['SERVER_SOFTWARE'];
		}
		else if (($sf = getenv('SERVER_SOFTWARE')))
		{
			$server = $sf;
		}
		else
		{
			$server = JText::_('K2MART_N_A');
		}
		$gd_check = extension_loaded('gd');
		$curl_check = extension_loaded('curl');
		$k2_check = JFile::exists(JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'k2.php');
		$virtuemart_check = JFile::exists(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'virtuemart.php');
		$this->assignRef('server', $server);
		$this->assignRef('php_version', $php_version);
		$this->assignRef('db_version', $db_version);
		$this->assignRef('gd_check', $gd_check);
		$this->assignRef('curl_check', $curl_check);
		$this->assignRef('k2_check', $k2_check);
		$this->assignRef('virtuemart_check', $virtuemart_check);
		$this->loadHelper('html');
		K2martHTMLHelper::title('K2MART_INFORMATION');
		K2martHTMLHelper::toolbar();
		K2martHTMLHelper::subMenu();
		parent::display($tpl);
	}

}
