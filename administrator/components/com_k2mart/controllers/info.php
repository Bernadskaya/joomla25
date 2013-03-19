<?php
/**
 * @version		$Id: info.php 2211 2012-11-20 14:40:34Z lefteris.kavadas $
 * @package		K2mart
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class K2martControllerInfo extends JController
{

	public function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('view', 'info');
		parent::display();
	}

}
